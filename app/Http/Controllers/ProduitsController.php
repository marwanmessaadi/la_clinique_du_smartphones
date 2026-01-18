<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produits;
use App\Models\Categorie;
use App\Models\Fournisseur;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Exception;

class ProduitsController extends Controller
{
    /**
     * Liste tous les produits avec pagination
     */
    public function index(Request $request)
    {
        $query = Produits::with(['categorie', 'fournisseur']);

        // Filtres de recherche
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        $produits = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('produits.index', compact('produits', 'categories', 'fournisseurs'));
    }

    /**
     * Affiche le formulaire de création
     */
    public function create()
    {
        $categories = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('produits.create', compact('categories', 'fournisseurs'));
    }

    /**
     * Enregistre un ou plusieurs produits
     */
    public function store(Request $request)
    {
        // Validation selon le contexte
        if ($request->has('produits')) {
            // Mode commande multiple
            $request->validate([
                'produits' => 'required|array|min:1',
                'produits.*.nom' => 'required|string|max:255',
                'produits.*.quantite' => 'required|integer|min:1',
                'produits.*.prix_achat' => 'required|numeric|min:0',
                'produits.*.prix_vente' => 'required|numeric|min:0',
                'produits.*.categorie_id' => 'required|exists:categories,id',
                'produits.*.fournisseur_id' => 'nullable|exists:fournisseurs,id',
            ]);

            return $this->storeMultiple($request);
        } else {
            // Mode création simple
            $request->validate([
                'nom' => 'required|string|max:255',
                'code' => 'nullable|string|max:50|unique:produits,code',
                'quantite' => 'required|integer|min:0',
                'prix_achat' => 'required|numeric|min:0',
                'prix_vente' => 'required|numeric|min:0',
                'prix_gros' => 'nullable|numeric|min:0',
                'categorie_id' => 'required|exists:categories,id',
                'fournisseur_id' => 'nullable|exists:fournisseurs,id',
                'date_achat' => 'nullable|date',
                'description' => 'nullable|string|max:1000',
                'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            ]);

            return $this->storeSingle($request);
        }
    }

    /**
     * Enregistre un seul produit
     */
    private function storeSingle(Request $request)
    {
        try {
            $produit = new Produits();
            $produit->nom = $request->nom;
            $produit->code = $request->code ?? $this->generateProductCode();
            $produit->quantite = $request->quantite;
            $produit->prix_achat = $request->prix_achat;
            $produit->prix_vente = $request->prix_vente;
            $produit->prix_gros = $request->prix_gros;
            $produit->categorie_id = $request->categorie_id;
            $produit->fournisseur_id = $request->fournisseur_id;
            $produit->date_achat = $request->date_achat;
            $produit->description = $request->description;
            $produit->etat = $request->etat ?? 'disponible';
            $produit->type = $request->type ?? 'achat';

            // Gestion de l'image
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('produits', 'public');
                $produit->image = $imagePath;
            }

            $produit->save();

            return redirect()->route('produits.index')
                ->with('success', 'Produit créé avec succès.');

        } catch (Exception $e) {
            Log::error('Erreur création produit: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la création du produit.')
                ->withInput();
        }
    }

    /**
     * Enregistre plusieurs produits (commande d'achat)
     */
    private function storeMultiple(Request $request)
    {
        DB::beginTransaction();

        try {
            $count = 0;
            foreach ($request->produits as $prodData) {
                // Vérifie si le produit existe déjà
                $produit = Produits::where('nom', $prodData['nom'])
                    ->where('categorie_id', $prodData['categorie_id'])
                    ->first();

                if ($produit) {
                    // Produit existant → augmente le stock et met à jour les prix
                    $produit->quantite += $prodData['quantite'];
                    $produit->prix_achat = $prodData['prix_achat'];
                    $produit->prix_vente = $prodData['prix_vente'];
                    
                    if (isset($prodData['fournisseur_id'])) {
                        $produit->fournisseur_id = $prodData['fournisseur_id'];
                    }
                    
                    if (isset($prodData['prix_gros'])) {
                        $produit->prix_gros = $prodData['prix_gros'];
                    }
                } else {
                    // Nouveau produit → crée
                    $produit = new Produits();
                    $produit->nom = $prodData['nom'];
                    $produit->code = $prodData['code'] ?? $this->generateProductCode();
                    $produit->quantite = $prodData['quantite'];
                    $produit->prix_achat = $prodData['prix_achat'];
                    $produit->prix_vente = $prodData['prix_vente'];
                    $produit->prix_gros = $prodData['prix_gros'] ?? null;
                    $produit->categorie_id = $prodData['categorie_id'];
                    $produit->fournisseur_id = $prodData['fournisseur_id'] ?? null;
                    $produit->etat = 'disponible';
                    $produit->type = 'achat';
                }

                $produit->save();
                $count++;
            }

            DB::commit();
            return redirect()->route('produits.index')
                ->with('success', "{$count} produit(s) enregistré(s) avec succès.");

        } catch (Exception $e) {
            DB::rollBack();
            Log::error('Erreur enregistrement multiple: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de l\'enregistrement des produits.')
                ->withInput();
        }
    }

    /**
     * Affiche un produit spécifique
     */
    public function show($id)
    {
        $produit = Produits::with(['categorie', 'fournisseur'])->findOrFail($id);
        return view('produits.show', compact('produit'));
    }
    
    /**
     * Affiche le formulaire d'édition
     */
    public function edit($id)
    {
        $produit = Produits::findOrFail($id);
        $categories = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        
        // Charge les commandes si la relation existe
        $commandes = method_exists($produit, 'commandes') 
            ? $produit->commandes()->orderByDesc('created_at')->get() 
            : collect();

        return view('produits.edit', compact('produit', 'categories', 'fournisseurs', 'commandes'));
    }

    /**
     * Met à jour un produit
     */
    public function update(Request $request, $id)
    {
        $produit = Produits::findOrFail($id);

        $request->validate([
            'nom' => 'required|string|max:255',
            'code' => 'nullable|string|max:50|unique:produits,code,' . $id,
            'quantite' => 'required|integer|min:0',
            'prix_achat' => 'required|numeric|min:0',
            'prix_vente' => 'required|numeric|min:0',
            'prix_gros' => 'nullable|numeric|min:0',
            'categorie_id' => 'required|exists:categories,id',
            'fournisseur_id' => 'nullable|exists:fournisseurs,id',
            'date_achat' => 'nullable|date',
            'description' => 'nullable|string|max:1000',
            'etat' => 'nullable|in:disponible,rupture,commande',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        try {
            $produit->nom = $request->nom;
            $produit->code = $request->code ?? $produit->code;
            $produit->quantite = $request->quantite;
            $produit->prix_achat = $request->prix_achat;
            $produit->prix_vente = $request->prix_vente;
            $produit->prix_gros = $request->prix_gros;
            $produit->categorie_id = $request->categorie_id;
            $produit->fournisseur_id = $request->fournisseur_id;
            $produit->date_achat = $request->date_achat;
            $produit->description = $request->description;
            $produit->etat = $request->etat ?? $produit->etat;

            // Gestion de l'image
            if ($request->hasFile('image')) {
                // Supprime l'ancienne image
                if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                    Storage::disk('public')->delete($produit->image);
                }
                
                $imagePath = $request->file('image')->store('produits', 'public');
                $produit->image = $imagePath;
            }

            $produit->save();

            return redirect()->route('produits.show', $produit->id)
                ->with('success', 'Produit mis à jour avec succès.');

        } catch (Exception $e) {
            Log::error('Erreur mise à jour produit: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la mise à jour.')
                ->withInput();
        }
    }

    /**
     * Supprime un produit
     */
    public function destroy($id)
    {
        $produit = Produits::findOrFail($id);

        try {
            // Supprime l'image si elle existe
            if ($produit->image && Storage::disk('public')->exists($produit->image)) {
                Storage::disk('public')->delete($produit->image);
            }

            $produit->delete();

            return redirect()->route('produits.index')
                ->with('success', 'Produit supprimé avec succès.');

        } catch (Exception $e) {
            Log::error('Erreur suppression produit: ' . $e->getMessage());
            return back()->with('error', 'Erreur lors de la suppression.');
        }
    }

    /**
     * Recherche de produits (API pour autocomplete)
     */
    public function search(Request $request)
    {
        $query = $request->get('query', '');
        $context = $request->get('context', 'create');
        
        $produits = Produits::with(['categorie', 'fournisseur'])
            ->where(function($q) use ($query) {
                $q->where('nom', 'like', "%{$query}%")
                  ->orWhere('code', 'like', "%{$query}%");
            })
            ->where('etat', '!=', 'supprime')
            ->limit(10)
            ->get()
            ->map(function($produit) use ($context) {
                return [
                    'id' => $produit->id,
                    'nom' => $produit->nom,
                    'code' => $produit->code,
                    'quantite' => $context === 'create' ? 1 : $produit->quantite,
                    'quantite_stock' => $produit->quantite,
                    'prix_achat' => $produit->prix_achat,
                    'prix_vente' => $produit->prix_vente,
                    'prix_gros' => $produit->prix_gros,
                    'categorie_id' => $produit->categorie_id,
                    'categorie_nom' => $produit->categorie->nom ?? '',
                    'fournisseur_id' => $produit->fournisseur_id,
                    'fournisseur_nom' => $produit->fournisseur->nom ?? '',
                    'context' => $context
                ];
            });
        
        return response()->json($produits);
    }

    /**
     * Produits d'achat avec filtres
     */
    public function indexAchat(Request $request)
    {
        $query = Produits::with(['categorie', 'fournisseur'])->where('type', 'achat');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('description', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        if ($request->filled('fournisseur_id')) {
            $query->where('fournisseur_id', $request->fournisseur_id);
        }

        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        if ($request->filled('prix_min')) {
            $query->where('prix_achat', '>=', $request->prix_min);
        }

        if ($request->filled('prix_max')) {
            $query->where('prix_achat', '<=', $request->prix_max);
        }

        $produits = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();

        return view('produits.index_achat', compact('produits', 'categories', 'fournisseurs'));
    }

    /**
     * Produits de vente
     */
    public function indexVente(Request $request)
    {
        $query = Produits::with(['categorie'])->where('type', 'vente');

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('nom', 'like', "%{$request->search}%")
                  ->orWhere('code', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $produits = $query->orderByDesc('created_at')->paginate(15)->withQueryString();
        $categories = Categorie::orderBy('nom')->get();

        return view('produits.index_vente', compact('produits', 'categories'));
    }

    /**
     * Impression de tickets pour tous les produits
     */
    public function printTickets()
    {
        $produits = Produits::with(['categorie'])->get();
        return view('produits.ticket', compact('produits'));
    }

    /**
     * Impression du ticket d'un seul produit
     */
    public function printSingleTicket($id)
    {
        $produit = Produits::with(['categorie'])->findOrFail($id);
        return view('produits.ticket', compact('produit'));
    }

    /**
     * Affiche le code-barres d'un produit
     */
    public function barcode(Produits $produit)
    {
        $barcode = $this->generateSimpleBarcode($produit->code ?? '000000');
        return view('produits.barcode', compact('produit', 'barcode'));
    }

    /**
     * Facture d'achat pour un produit
     */
    public function factureAchat($id)
    {
        $produit = Produits::with(['categorie', 'fournisseur'])->findOrFail($id);
        $barcode = $this->generateSimpleBarcode($produit->code ?? '000000');
        
        return view('produits.facture-achat', compact('produit', 'barcode'));
    }

    /**
     * Génère un code produit unique
     */
    private function generateProductCode()
    {
        do {
            $code = 'PROD-' . strtoupper(Str::random(8));
        } while (Produits::where('code', $code)->exists());

        return $code;
    }

    /**
     * Génère un code-barres HTML simple
     */
    private function generateSimpleBarcode($code)
    {
        if (empty($code)) {
            $code = '000000';
        }
        
        return '
        <div style="text-align: center; margin: 20px 0; padding: 15px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 8px;">
            <div style="font-family: \'Courier New\', monospace; font-size: 28px; letter-spacing: 5px; 
                        font-weight: bold; padding: 15px; background: white; border: 2px solid #000; border-radius: 4px;">
                ' . htmlspecialchars($code) . '
            </div>
            <div style="margin-top: 12px; font-family: \'Courier New\', monospace; font-size: 14px; color: #666;">
                Code: ' . htmlspecialchars($code) . '
            </div>
        </div>';
    }
}