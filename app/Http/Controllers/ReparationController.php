<?php

namespace App\Http\Controllers;

use App\Models\Reparation;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ReparationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Liste des réparations avec filtres et statistiques
     */
    public function index(Request $request)
    {
        $query = Reparation::query();

        // Filtre de recherche
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('produit', 'like', "%{$search}%");
            });
        }

        // Filtre par état
        if ($request->filled('etat') && in_array($request->etat, ['en_cours', 'terminee', 'annulee'])) {
            $query->where('etat', $request->etat);
        }

        // Filtre par période
        if ($request->filled('date_debut')) {
            $query->whereDate('date_reparation', '>=', $request->date_debut);
        }
        if ($request->filled('date_fin')) {
            $query->whereDate('date_reparation', '<=', $request->date_fin);
        }

        // Filtre par prix
        if ($request->filled('prix_min')) {
            $query->where('prix', '>=', floatval($request->prix_min));
        }
        if ($request->filled('prix_max')) {
            $query->where('prix', '<=', floatval($request->prix_max));
        }

        // Tri sécurisé
        $allowedSorts = ['nom', 'date_reparation', 'prix', 'etat', 'created_at'];
        $sortField = in_array($request->sort, $allowedSorts) ? $request->sort : 'date_reparation';
        $sortOrder = $request->order === 'asc' ? 'asc' : 'desc';
        
        $query->orderBy($sortField, $sortOrder);

        // Pagination
        $reparations = $query->paginate(15)->withQueryString();

        // Statistiques
        $stats = $this->getStatistics();

        return view('reparations.index', compact('reparations', 'stats'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        return view('reparations.create');
    }

    /**
     * Enregistre une nouvelle réparation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'prix' => 'required|numeric|min:0|max:999999.99',
            'date_reparation' => 'nullable|date',
            'produit' => 'required|string|max:255',
            'etat' => 'required|in:en_cours,terminee,annulee',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Générer le code unique
            $validated['code'] = $this->generateUniqueCode();

            // Gérer la date
            $validated['date_reparation'] = $request->filled('date_reparation')
                ? Carbon::parse($request->date_reparation)
                : Carbon::now();

            // Créer la réparation
            $reparation = Reparation::create($validated);

            // Journalisation
            Log::info('Réparation créée', [
                'id' => $reparation->id,
                'code' => $reparation->code,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('reparation.show', $reparation)
                ->with('success', "Réparation créée avec succès. Code: {$reparation->code}");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur création réparation: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la création de la réparation.')
                ->withInput();
        }
    }

    /**
     * Affiche une réparation spécifique
     */
    public function show(Reparation $reparation)
    {
        return view('reparations.show', compact('reparation'));
    }

    /**
     * Affiche le formulaire d'édition
     */
    public function edit(Reparation $reparation)
    {
        return view('reparations.edit', compact('reparation'));
    }

    /**
     * Met à jour une réparation
     */
    public function update(Request $request, Reparation $reparation)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'prix' => 'required|numeric|min:0|max:999999.99',
            'date_reparation' => 'nullable|date',
            'produit' => 'required|string|max:255',
            'etat' => 'required|in:en_cours,terminee,annulee',
            'notes' => 'nullable|string|max:1000',
        ]);

        DB::beginTransaction();

        try {
            // Gérer la date
            if ($request->filled('date_reparation')) {
                $validated['date_reparation'] = Carbon::parse($request->date_reparation);
            }

            // Mettre à jour
            $reparation->update($validated);

            Log::info('Réparation mise à jour', [
                'id' => $reparation->id,
                'user_id' => Auth::id()
            ]);

            DB::commit();

            return redirect()->route('reparation.show', $reparation)
                ->with('success', 'Réparation mise à jour avec succès.');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur mise à jour réparation: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la mise à jour.')
                ->withInput();
        }
    }

    /**
     * Supprime une réparation
     */
    public function destroy(Reparation $reparation)
    {
        $code = $reparation->code;

        try {
            $reparation->delete();

            Log::info('Réparation supprimée', [
                'code' => $code,
                'user_id' => Auth::id()
            ]);

            return redirect()->route('reparation.index')
                ->with('success', 'Réparation supprimée avec succès.');

        } catch (Exception $e) {
            Log::error('Erreur suppression réparation: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Affiche le code-barres d'une réparation
     */
    public function barcode(Reparation $reparation)
    {
        try {
            // Générer un code si manquant
            if (!$reparation->code) {
                $reparation->code = $this->generateUniqueCode();
                $reparation->save();
            }

            $barcode = $this->generateBarcodeHTML($reparation->code);
            
            return view('reparations.barcode', compact('reparation', 'barcode'));
            
        } catch (Exception $e) {
            Log::error('Erreur génération code-barres: ' . $e->getMessage());
            
            return redirect()->route('reparation.show', $reparation)
                ->with('error', 'Erreur lors de la génération du code-barres.');
        }
    }

    /**
     * Imprime le ticket de réparation
     */
    public function ticket(Reparation $reparation)
    {
        try {
            $barcode = $this->generateBarcodeHTML($reparation->code);
            
            $type = 'reparation';
            $data = [
                'code' => $reparation->code,
                'nom' => $reparation->nom,
                'produit' => $reparation->produit,
                'description' => $reparation->description,
                'prix' => $reparation->prix,
                'notes' => $reparation->notes,
            ];
            
            return view('ticket', compact('reparation', 'barcode', 'type', 'data'));

        } catch (Exception $e) {
            Log::error('Erreur génération ticket: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de la génération du ticket.');
        }
    }

    /**
     * Recherche avancée (utilise la méthode index)
     */
    public function search(Request $request)
    {
        return $this->index($request);
    }

    /**
     * Génère un code unique pour la réparation
     */
    private function generateUniqueCode()
    {
        $year = date('Y');
        $attempts = 0;
        $maxAttempts = 100;

        do {
            // Récupère le dernier numéro de l'année en cours
            $latestReparation = Reparation::where('code', 'like', "REP{$year}%")
                ->orderBy('code', 'desc')
                ->first();

            if ($latestReparation && preg_match('/REP' . $year . '(\d{6})/', $latestReparation->code, $matches)) {
                $number = intval($matches[1]) + 1;
            } else {
                $number = 1;
            }

            $code = 'REP' . $year . str_pad($number, 6, '0', STR_PAD_LEFT);
            $attempts++;

            if ($attempts >= $maxAttempts) {
                throw new Exception('Impossible de générer un code unique après ' . $maxAttempts . ' tentatives');
            }

        } while (Reparation::where('code', $code)->exists());

        return $code;
    }

    /**
     * Génère un code-barres HTML avec SVG
     */
    private function generateBarcodeHTML($code)
    {
        if (empty($code)) {
            $code = '000000';
        }

        // Génère un code-barres SVG
        $barcodeSVG = $this->generateCode128SVG($code);
        
        return '
        <div style="text-align: center; margin: 10px 0; padding: 10px; background: white;">
            ' . $barcodeSVG . '
            <div style="font-family: \'Courier New\', monospace; font-size: 11px; margin-top: 5px; letter-spacing: 2px;">
                ' . htmlspecialchars($code) . '
            </div>
        </div>';
    }

    /**
     * Génère un code-barres SVG simple (Code 128)
     */
    private function generateCode128SVG($code)
    {
        // Pattern simple pour représenter le code
        $width = strlen($code) * 12;
        $height = 50;
        
        $svg = '<svg width="' . $width . '" height="' . $height . '" xmlns="http://www.w3.org/2000/svg">';
        
        // Génère des barres basées sur les caractères
        $x = 0;
        for ($i = 0; $i < strlen($code); $i++) {
            $charCode = ord($code[$i]);
            $barWidth = ($charCode % 3) + 2;
            
            // Alterne noir/blanc
            if ($i % 2 == 0) {
                $svg .= '<rect x="' . $x . '" y="0" width="' . $barWidth . '" height="' . $height . '" fill="black"/>';
            }
            
            $x += $barWidth + 1;
        }
        
        $svg .= '</svg>';
        
        return $svg;
    }

    /**
     * Calcule les statistiques des réparations
     */
    private function getStatistics()
    {
        try {
            return [
                'total' => Reparation::count(),
                'en_cours' => Reparation::where('etat', 'en_cours')->count(),
                'terminees' => Reparation::where('etat', 'terminee')->count(),
                'annulees' => Reparation::where('etat', 'annulee')->count(),
                'montant_total' => Reparation::sum('prix'),
                'montant_mois' => Reparation::whereMonth('date_reparation', Carbon::now()->month)
                    ->whereYear('date_reparation', Carbon::now()->year)
                    ->sum('prix'),
            ];
        } catch (Exception $e) {
            Log::error('Erreur calcul statistiques: ' . $e->getMessage());
            
            return [
                'total' => 0,
                'en_cours' => 0,
                'terminees' => 0,
                'annulees' => 0,
                'montant_total' => 0,
                'montant_mois' => 0,
            ];
        }
    }

    /**
     * Change l'état d'une réparation
     */
    public function updateStatus(Request $request, Reparation $reparation)
    {
        $request->validate([
            'etat' => 'required|in:en_cours,terminee,annulee'
        ]);

        try {
            $oldStatus = $reparation->etat;
            $reparation->etat = $request->etat;
            $reparation->save();

            Log::info('État réparation modifié', [
                'id' => $reparation->id,
                'ancien_etat' => $oldStatus,
                'nouvel_etat' => $request->etat,
                'user_id' => Auth::id()
            ]);

            return redirect()->back()
                ->with('success', 'État de la réparation mis à jour avec succès.');

        } catch (Exception $e) {
            Log::error('Erreur changement état: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors du changement d\'état.');
        }
    }

    /**
     * Export des réparations en CSV
     */
    public function export(Request $request)
    {
        try {
            $query = Reparation::query();

            // Appliquer les mêmes filtres que l'index
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('code', 'like', "%{$search}%")
                      ->orWhere('produit', 'like', "%{$search}%");
                });
            }

            if ($request->filled('etat')) {
                $query->where('etat', $request->etat);
            }

            if ($request->filled('date_debut')) {
                $query->whereDate('date_reparation', '>=', $request->date_debut);
            }

            if ($request->filled('date_fin')) {
                $query->whereDate('date_reparation', '<=', $request->date_fin);
            }

            $reparations = $query->orderBy('date_reparation', 'desc')->get();

            $filename = 'reparations_' . date('Y-m-d_His') . '.csv';
            
            $headers = [
                'Content-Type' => 'text/csv; charset=UTF-8',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ];

            $callback = function() use ($reparations) {
                $file = fopen('php://output', 'w');
                fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // UTF-8 BOM
                
                // En-têtes
                fputcsv($file, ['Code', 'Nom', 'Produit', 'Prix', 'État', 'Date', 'Description', 'Notes'], ';');
                
                // Données
                foreach ($reparations as $reparation) {
                    fputcsv($file, [
                        $reparation->code,
                        $reparation->nom,
                        $reparation->produit,
                        number_format($reparation->prix, 2, ',', ' '),
                        $reparation->etat,
                        $reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y') : '',
                        $reparation->description,
                        $reparation->notes,
                    ], ';');
                }
                
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);

        } catch (Exception $e) {
            Log::error('Erreur export CSV: ' . $e->getMessage());
            
            return back()->with('error', 'Erreur lors de l\'export.');
        }
    }
}