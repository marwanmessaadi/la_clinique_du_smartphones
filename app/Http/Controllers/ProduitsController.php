<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produits;
use App\Models\Categorie;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Milon\Barcode\DNS1D;

class ProduitsController extends Controller
{
    /**
     * Affiche la liste des produits.
     */
    public function index()
    {
        $produits = Produits::all();
        return view('produits.index', compact('produits'));
    }

    /**
     * Affiche la liste des produits d'achat.
     */
    public function indexAchat(Request $request)
    {
        $query = Produits::where('type', 'achat')->with(['categorie', 'fournisseur']);

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par fournisseur
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        // Filtre par prix
        if ($request->filled('prix_min')) {
            $query->where('prix_achat', '>=', $request->prix_min);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix_achat', '<=', $request->prix_max);
        }

        $produits = $query->paginate(15);
        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $fournisseurs = \App\Models\Fournisseur::orderBy('nom')->get();

        return view('produits.index_achat', compact('produits', 'categories', 'fournisseurs'));
    }

    /**
     * Affiche la liste des produits de vente.
     */
    public function indexVente(Request $request)
    {
        $query = Produits::where('type', 'vente')->with(['categorie', 'fournisseur']);

        // Recherche
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('nom', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%')
                  ->orWhere('code', 'like', '%' . $request->search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        // Filtre par fournisseur
        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        // Filtre par état
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        // Filtre par prix
        if ($request->filled('prix_min')) {
            $query->where('prix_vente', '>=', $request->prix_min);
        }
        if ($request->filled('prix_max')) {
            $query->where('prix_vente', '<=', $request->prix_max);
        }

        $produits = $query->paginate(15);
        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $fournisseurs = \App\Models\Fournisseur::orderBy('nom')->get();

        return view('produits.index_vente', compact('produits', 'categories', 'fournisseurs'));
    }

    /**
     * Affiche le formulaire de création.
     */
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.create', compact('categories'));
    }

    /**
     * Enregistre un nouveau produit.
     */
    public function store(Request $request)
    {
        $produits = $request->input('produits', []);
        $errors = [];
        $successCount = 0;

        foreach ($produits as $idx => $prod) {
            $validator = \Validator::make($prod, [
                'nom' => 'required|string|max:255',
                'description' => 'nullable|string',
                'date_achat' => 'nullable|date',
                'prix_achat' => 'required|numeric',
                'prix_vente' => 'required|numeric',
                'prix_gros' => 'nullable|numeric',
                'quantite' => 'nullable|integer|min:0',
                'categorie_id' => 'required|exists:categories,id',
                'fournisseur_id' => 'nullable|exists:fournisseurs,id',
                'type' => 'nullable|in:achat,vente',
            ]);

            if ($validator->fails()) {
                $errors[$idx] = $validator->errors()->all();
                continue;
            }

            try {
                // Recherche par code/barcode si présent
                $produitExistant = null;
                if (!empty($prod['code'])) {
                    $produitExistant = Produits::where('code', $prod['code'])->first();
                }

                if ($produitExistant) {
                    // Produit existe : augmenter le stock et mettre à jour les infos
                    $produitExistant->quantite += $prod['quantite'] ?? 0;
                    $produitExistant->prix_achat = $prod['prix_achat'];
                    $produitExistant->prix_vente = $prod['prix_vente'];
                    $produitExistant->prix_gros = $prod['prix_gros'] ?? $produitExistant->prix_gros;
                    $produitExistant->fournisseur_id = $prod['fournisseur_id'] ?? $produitExistant->fournisseur_id;
                    $produitExistant->date_achat = !empty($prod['date_achat']) ? Carbon::parse($prod['date_achat']) : Carbon::now();
                    // Ne jamais modifier le code existant !
                    // if (!empty($prod['code'])) { $produitExistant->code = $produitExistant->code; }
                    if ($request->hasFile('produits.' . $idx . '.image')) {
                        $produitExistant->image = $request->file('produits.' . $idx . '.image')->store('produits', 'public');
                    }
                    $produitExistant->save();
                    $successCount++;
                } else {
                    // Nouveau produit
                    $produit = new Produits();
                    $produit->nom = $prod['nom'];
                    $produit->description = $prod['description'] ?? null;
                    $produit->prix_achat = $prod['prix_achat'];
                    $produit->prix_vente = $prod['prix_vente'];
                    $produit->prix_gros = $prod['prix_gros'] ?? null;
                    $produit->quantite = $prod['quantite'] ?? 0;
                    $produit->categorie_id = $prod['categorie_id'];
                    $produit->fournisseur_id = $prod['fournisseur_id'] ?? null;
                    $produit->type = $prod['type'] ?? 'achat';
                    $produit->etat = 'disponible';
                    $produit->date_achat = !empty($prod['date_achat']) ? Carbon::parse($prod['date_achat']) : Carbon::now();
                    if (!empty($prod['code'])) {
                        $produit->code = $prod['code'];
                    }
                    if ($request->hasFile('produits.' . $idx . '.image')) {
                        $produit->image = $request->file('produits.' . $idx . '.image')->store('produits', 'public');
                    }
                    $produit->save();
                    $this->generateBarcodeForProduit($produit);
                    $successCount++;
                }
            } catch (\Exception $e) {
                $errors[$idx][] = 'Erreur lors de l\'ajout du produit: ' . $e->getMessage();
            }
        }

        if (!empty($errors)) {
            return back()->withInput()->withErrors(['multi' => $errors]);
        }

        return redirect()->route('produits.index')->with('success', $successCount . ' produit(s) ajouté(s) avec succès.');
    }

    /**
     * Affiche un produit.
     */
    public function show(string $id)
    {
        $produit = Produits::findOrFail($id);
        return view('produits.show', compact('produit'));
    }

    /**
     * Affiche le formulaire d'édition.
     */
    public function edit(string $id)
    {
        $produit = Produits::findOrFail($id);
        $categories = Categorie::orderBy('nom')->get();
        return view('produits.edit', compact('produit', 'categories'));
    }

    /**
     * Met à jour un produit.
     */
    public function update(Request $request, Produits $produit)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date_achat' => 'nullable|date', // ajouté
            'prix_achat' => 'required|numeric',
            'prix_vente' => 'required|numeric',
            'prix_gros' => 'nullable|numeric',
            'quantite' => 'nullable|integer|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'type' => 'nullable|in:achat,vente',
            'image' => 'nullable|image|max:2048',
        ]);

        try {
            $produit->nom = $validated['nom'];
            $produit->description = $validated['description'] ?? $produit->description;
            $produit->prix_achat = $validated['prix_achat'];
            $produit->prix_vente = $validated['prix_vente'];
            $produit->prix_gros = $validated['prix_gros'] ?? $produit->prix_gros;
            $produit->quantite = $validated['quantite'] ?? $produit->quantite;
            $produit->categorie_id = $validated['categorie_id'];
            $produit->fournisseur_id = $validated['fournisseur_id'] ?? $produit->fournisseur_id;
            $produit->type = $validated['type'] ?? $produit->type;

            // mettre à jour date_achat si fournie
            if (!empty($validated['date_achat'])) {
                $produit->date_achat = Carbon::parse($validated['date_achat']);
            }

            if ($request->hasFile('image')) {
                // optionnel : supprimer ancienne image si existante
                if ($produit->image) {
                    Storage::disk('public')->delete($produit->image);
                }
                $imagePath = $request->file('image')->store('produits', 'public');
                $produit->image = $imagePath;
            }

            $produit->save();

            // Generate barcode if not exists
            $this->generateBarcodeForProduit($produit);

            return redirect()->route('produits.index')->with('success', 'Produit mis à jour avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la mise à jour du produit: ' . $e->getMessage());
            return back()->withInput()->withErrors(['error' => 'Erreur lors de la mise à jour du produit.']);
        }
    }

    /**
     * Supprime un produit.
     */
    public function destroy(string $id)
    {
        $produit = Produits::findOrFail($id);

        try {
            // supprimer image si stockée
            if ($produit->image) {
                Storage::disk('public')->delete($produit->image);
            }
            $produit->delete();

            return redirect()->route('produits.index')->with('success', 'Produit supprimé avec succès.');
        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression du produit: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Impossible de supprimer le produit.']);
        }
    }
    public function produitsDashbord()
    {
        $produits = Produits::all();
        return view('index', compact('produits'));
    }

    /**
     * Requête AJAX pour chercher un produit par nom (vente).
     */
    public function searchByName(Request $request)
    {
        $query = $request->get('query');
        $produits = Produits::where('type', 'vente')
            ->where('quantite', '>', 0)
            ->where('nom', 'like', "%{$query}%")
            ->limit(10)
            ->get(['id', 'nom', 'prix_vente', 'quantite']);

        return response()->json($produits);
    }

    /**
     * Display the barcode for the product.
     */
    public function barcode(Produits $produit)
    {
        try {
            // Generate barcode if it doesn't exist
            $code = $this->generateBarcodeForProduit($produit);

            $barcode = (new DNS1D())->getBarcodeHTML($code, 'C128', 2, 50);
            return view('produits.barcode', compact('produit', 'barcode'));
        } catch (\Exception $e) {
            return redirect()->route('produits.index')
                ->with('error', 'Erreur lors de la génération du code-barres.');
        }
    }

    /**
     * Print ticket for the product.
     */
    public function printTicket(Produits $produit)
    {
        try {
            // Generate barcode if it doesn't exist
            $code = $this->generateBarcodeForProduit($produit);

            $barcode = (new DNS1D())->getBarcodeHTML($code, 'C128', 1, 30);
            return view('produits.ticket', compact('produit', 'barcode'));
        } catch (\Exception $e) {
            return redirect()->route('produits.show', $produit)
                ->with('error', 'Erreur lors de la génération du ticket.');
        }
    }

    /**
     * API endpoint to get product information for AJAX requests.
     */
    public function apiShow(Produits $produit)
    {
        return response()->json([
            'id' => $produit->id,
            'nom' => $produit->nom,
            'prix_vente' => $produit->prix_vente,
            'quantite' => $produit->quantite,
            'description' => $produit->description,
            'categorie' => $produit->categorie ? $produit->categorie->nom : null,
        ]);
    }

    /**
     * Generate barcode for product.
     */
    private function generateBarcodeForProduit(Produits $produit)
    {
        if ($produit->code) {
            // Vérifier si le code est déjà utilisé par un autre produit
            $exists = Produits::where('code', $produit->code)
                ->where('id', '!=', $produit->id)
                ->exists();
            if (!$exists) {
                return $produit->code;
            }
        }

        // Optimisation : ne cherche que le code max de l'année courante
        $year = date('Y');
        $prefix = 'PROD' . $year;
        $maxCode = Produits::where('code', 'like', $prefix . '%')
            ->selectRaw('MAX(code) as max_code')
            ->value('max_code');

        if ($maxCode) {
            $lastNumber = intval(substr($maxCode, -6));
            $number = $lastNumber + 1;
        } else {
            $number = 1;
        }
        $code = $prefix . str_pad($number, 6, '0', STR_PAD_LEFT);

        $produit->update(['code' => $code]);

        return $code;
    }

    public function printFactureAchat(Produits $produit)
    {
        // Vérifier que c'est bien un produit d'achat
        if ($produit->type !== 'achat') {
            abort(404, 'Cette facture n\'est disponible que pour les produits achetés.');
        }

        $produit->load(['categorie', 'fournisseur']);

        // Générer le code-barres si nécessaire
        $code = $this->generateBarcodeForProduit($produit);
        $barcode = (new DNS1D())->getBarcodeHTML($code, 'C128', 1, 40);

        return view('produits.facture-achat', compact('produit', 'barcode'));
    }
}
