<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use Illuminate\Http\Request;
use Milon\Barcode\DNS1D;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class ReparationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Reparation::query();

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%$search%")
                  ->orWhere('description', 'like', "%$search%")
                  ->orWhere('code', 'like', "%$search%")
                  ->orWhere('produit', 'like', "%$search%")
                  ->orWhere('etat', 'like', "%$search%");
            });
        }

        $reparations = $query->latest()->paginate(10);
        return view('reparations.index', compact('reparations'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('reparations.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'date_reparation' => 'nullable|date', // accepted if provided
            'produit' => 'required|string|max:255',
            'etat' => 'required|in:en_cours,terminee,annulee',
        ]);

        // Generate unique barcode
        $latestReparation = Reparation::latest()->first();
        $number = $latestReparation && $latestReparation->code
            ? intval(substr($latestReparation->code, -6)) + 1
            : 1;
        $validated['code'] = 'REP' . date('Y') . str_pad($number, 6, '0', STR_PAD_LEFT);

        // Ensure datetime with current date+time when not provided
        $validated['date_reparation'] = $request->filled('date_reparation')
            ? Carbon::parse($request->input('date_reparation'))
            : Carbon::now();

        $reparation = Reparation::create($validated);

        return view('ticket', [
            'type' => 'reparation',
            'data' => $reparation
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reparation $reparation)
    {
        return view('reparations.show', compact('reparation'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reparation $reparation)
    {
        return view('reparations.edit', compact('reparation'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Reparation $reparation)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'date_reparation' => 'nullable|date',
            'produit' => 'required|string|max:255',
            'etat' => 'required|in:en_cours,terminee,annulee',
            'code' => 'nullable|string|unique:reparations,code,' . $reparation->id,
        ]);

        // Generate code if not provided and doesn't exist
        if (empty($validated['code']) && !$reparation->code) {
            $validated['code'] = $this->generateBarcodeForRepair($reparation);
        }

        $reparation->update($validated);

        return redirect()->route('reparation.index')
            ->with('success', 'Réparation mise à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reparation $reparation)
    {
        $reparation->delete();

        return redirect()->route('reparation.index')
            ->with('success', 'Réparation supprimée avec succès.');
    }

    /**
     * Search reparations by multiple criteria.
     */
    public function search(Request $request)
    {
        try {
            $query = Reparation::query();

            // Search by name (case-insensitive)
            if ($request->filled('nom')) {
                $query->where('nom', 'like', '%' . trim($request->input('nom')) . '%');
            }

            // Search by exact barcode
            if ($request->filled('code')) {
                $barcode = trim($request->input('code'));
                $query->where('code', 'like', '%' . $barcode . '%');
            }

            // Filter by status
            if ($request->filled('etat')) {
                $query->where('etat', $request->input('etat'));
            }

            // Date range filter
            if ($request->filled('date_debut')) {
                $query->whereDate('date_reparation', '>=', $request->input('date_debut'));
            }
            if ($request->filled('date_fin')) {
                $query->whereDate('date_reparation', '<=', $request->input('date_fin'));
            }

            // Price range filter
            if ($request->filled('prix_min')) {
                $query->where('prix', '>=', $request->input('prix_min'));
            }
            if ($request->filled('prix_max')) {
                $query->where('prix', '<=', $request->input('prix_max'));
            }

            // Product search
            if ($request->filled('produit')) {
                $query->where('produit', 'like', '%' . trim($request->input('produit')) . '%');
            }

            // Sort results
            $sortField = $request->input('sort', 'date_reparation');
            $sortOrder = $request->input('order', 'desc');
            $allowedSortFields = ['nom', 'date_reparation', 'prix', 'etat'];
            
            if (in_array($sortField, $allowedSortFields)) {
                $query->orderBy($sortField, $sortOrder === 'asc' ? 'asc' : 'desc');
            }

            // Paginate results
            $reparations = $query->latest()
                ->paginate(10)
                ->withQueryString(); // Preserve URL parameters

            // Get stats for the filtered results
            $stats = [
                'total' => $query->count(),
                'en_cours' => $query->where('etat', 'en_cours')->count(),
                'terminees' => $query->where('etat', 'terminee')->count(),
                'montant_total' => $query->sum('prix')
            ];

            return view('reparations.index', compact('reparations', 'stats'))
                ->with('search', $request->all());
        } catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage());
            return redirect()->route('reparation.index')
                ->with('error', 'Une erreur est survenue lors de la recherche.');
        }
    }

    /**
     * Display the barcode for the repair.
     */
    public function barcode(Reparation $reparation)
    {
        try {
            // Generate barcode if it doesn't exist
            $code = $this->generateBarcodeForRepair($reparation);

            $barcode = (new DNS1D())->getBarcodeHTML($code, 'C128', 2, 50);
            return view('reparations.barcode', compact('reparation', 'barcode'));
        } catch (\Exception $e) {
            return redirect()->route('reparation.index')
                ->with('error', 'Erreur lors de la génération du code-barres.');
        }
    }

    /**
     * Validate barcode uniqueness.
     */
    private function validateUniqueBarcode($barcode, $excludeId = null)
    {
        $query = Reparation::where('code', $barcode);

        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        return !$query->exists();
    }

    /**
     * Generate barcode for a repair if it doesn't exist.
     */
    private function generateBarcodeForRepair(Reparation $reparation)
    {
        if ($reparation->code) {
            return $reparation->code;
        }

        $latestReparation = Reparation::whereNotNull('code')->latest()->first();
        $number = $latestReparation && $latestReparation->code
            ? intval(substr($latestReparation->code, -6)) + 1
            : 1;
        $code = 'REP' . date('Y') . str_pad($number, 6, '0', STR_PAD_LEFT);

        $reparation->update(['code' => $code]);

        return $code;
    }
}
