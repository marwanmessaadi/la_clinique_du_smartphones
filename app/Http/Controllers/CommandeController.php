<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Commande;
use App\Models\Produits;
use App\Models\Fournisseur;
use App\Models\Categorie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Schema;
use Exception;
use Throwable;

class CommandeController extends Controller
{
    /**
     * Afficher la liste des commandes
     */
    public function index(Request $request)
{
    $query = Commande::with(['fournisseur', 'produits.categorie']);

    // Recherche
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('numero_commande', 'like', "%{$search}%")
              ->orWhereHas('produits', function($q2) use ($search) {
                  $q2->where('nom', 'like', "%{$search}%");
              })
              ->orWhereHas('fournisseur', function($q3) use ($search) {
                  $q3->where('nom', 'like', "%{$search}%");
              });
        });
    }

    // Filtres
    if ($request->filled('fournisseur_id')) {
        $query->where('fournisseur_id', $request->fournisseur_id);
    }
    if ($request->filled('statut')) {
        $query->where('statut', $request->statut);
    }
    if ($request->filled('date_debut')) {
        $query->whereDate('date_commande', '>=', $request->date_debut);
    }
    if ($request->filled('date_fin')) {
        $query->whereDate('date_commande', '<=', $request->date_fin);
    }

    $commandes = $query->orderByDesc('date_commande')
                       ->paginate(15)
                       ->withQueryString();

    $fournisseurs = Fournisseur::orderBy('nom')->get();
    $categories = Categorie::orderBy('nom')->get(); // Ajoutez cette ligne
    $statuts = [
        'en_attente' => 'En attente',
        'recue' => 'Reçue',
        'annulee' => 'Annulée'
    ];

    return view('commandes.index', compact('commandes', 'fournisseurs', 'categories', 'statuts'));
}

    /**
     * Afficher les détails d'une commande
     */
    public function show($id)
    {
        $commande = Commande::with(['fournisseur', 'produits.categorie'])->findOrFail($id);
        return view('commandes.show', compact('commande'));
    }

    /**
     * Afficher le formulaire de création
     */
    /**
 * Afficher le formulaire de création
 */
/**
 * Afficher le formulaire de création
 */
public function create()
{
    try {
        $produits = Produits::with('categorie')->orderBy('nom')->get();
        $fournisseurs = Fournisseur::orderBy('nom')->get();
        $categories = Categorie::orderBy('nom')->get();
        
        // Vérification pour le debug
        Log::info('Formulaire création commande', [
            'nb_produits' => $produits->count(),
            'nb_fournisseurs' => $fournisseurs->count(),
            'nb_categories' => $categories->count(),
        ]);
        
        // Si aucune catégorie n'existe, créer des catégories par défaut
        if ($categories->isEmpty()) {
            Log::warning('Aucune catégorie trouvée, création automatique');
            
            Categorie::create(['nom' => 'Smartphones']);
            Categorie::create(['nom' => 'Accessoires']);
            Categorie::create(['nom' => 'Pièces détachées']);
            
            $categories = Categorie::orderBy('nom')->get();
        }
        
        return view('commandes.create', compact('produits', 'fournisseurs', 'categories'));
        
    } catch (Exception $e) {
        Log::error('Erreur formulaire commande:', ['error' => $e->getMessage()]);
        
        return back()->with('error', 'Erreur lors du chargement du formulaire: ' . $e->getMessage());
    }
}
    /**
     * Enregistrer une nouvelle commande
     */
/**
 * Enregistrer une nouvelle commande
 */
/**
 * Enregistrer une nouvelle commande
 */
public function store(Request $request)
{
    Log::info('================ DÉBUT STORE COMMANDE ================');
    Log::info('Données reçues:', $request->all());
    
    // ===== 1. VALIDATION =====
    if (!$request->has('produits') || empty($request->produits)) {
        return back()->withInput()->with('error', 'Veuillez ajouter au moins un produit.');
    }

    try {
        // CORRECTION: Vérifiez si votre table s'appelle 'fournisseur' ou 'fournisseurs'
        $fournisseurTable = Schema::hasTable('fournisseurs') ? 'fournisseurs' : 'fournisseur';
        
        $request->validate([
            'fournisseur_id' => 'nullable|exists:'.$fournisseurTable.',id', // CORRIGÉ
            'notes' => 'nullable|string|max:500',
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'nullable|exists:produits,id',
            'produits.*.nom' => 'nullable|string|max:255',
            'produits.*.categorie_id' => 'required|exists:categories,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_achat' => 'required|numeric|min:0',
            'produits.*.prix_vente' => 'required|numeric|min:0',
        ]);
    } catch (ValidationException $e) {
        Log::error('Erreur validation:', $e->errors());
        return back()->withErrors($e->errors())->withInput();
    }

    // ===== 2. VALIDATION MÉTIER =====
    foreach ($request->produits as $key => $produit) {
        if (empty($produit['id']) && empty($produit['nom'])) {
            throw ValidationException::withMessages([
                "produits.$key.nom" => 'Le nom du produit est obligatoire',
            ]);
        }
    }

    // ===== 3. TRANSACTION =====
    DB::beginTransaction();

    try {
        // ===== 4. CRÉATION COMMANDE =====
        $commande = Commande::create([
            'numero_commande' => Commande::genererNumeroCommande(),
            'fournisseur_id' => $request->fournisseur_id,
            'date_commande' => now(),
            'statut' => 'en_attente',
            'notes' => $request->notes,
            'montant_total' => 0,
        ]);

        Log::info('Commande créée:', ['id' => $commande->id, 'numero' => $commande->numero_commande]);

        $totalGeneral = 0;
        $pivotData = [];

        // ===== 5. TRAITEMENT DES PRODUITS =====
        foreach ($request->produits as $index => $data) {
            Log::info("Traitement produit #$index", $data);

            $quantite = (int) $data['quantite'];
            $prixAchat = (float) $data['prix_achat'];
            $prixVente = (float) $data['prix_vente'];
            
            // ===== PRODUIT EXISTANT =====
            if (!empty($data['id'])) {
                $produitExistant = Produits::findOrFail($data['id']);
                
                // Vérifier si les prix sont différents
                $prixDifferent = 
                    abs($produitExistant->prix_achat - $prixAchat) > 0.01 ||
                    abs($produitExistant->prix_vente - $prixVente) > 0.01;
                
                if ($prixDifferent) {
                    // Prix différents → créer une NOUVELLE variante du produit
                    $code = 'VAR-' . now()->format('YmdHis') . '-' . random_int(100, 999);
                    
                    $produit = Produits::create([
                        'nom' => $produitExistant->nom . ' (Var. ' . date('d/m') . ')',
                        'code' => $code,
                        'description' => $produitExistant->description,
                        'categorie_id' => $produitExistant->categorie_id,
                        'fournisseur_id' => $request->fournisseur_id ?? $produitExistant->fournisseur_id,
                        'quantite' => 0,
                        'prix_achat' => $prixAchat,
                        'prix_vente' => $prixVente,
                        'prix_gros' => $data['prix_gros'] ?? $produitExistant->prix_gros,
                        'etat' => 'disponible',
                        'type' => 'achat',
                        'marque' => $produitExistant->marque ?? null,
                    ]);
                    
                    Log::info('Nouvelle variante créée:', [
                        'original_id' => $produitExistant->id,
                        'nouveau_id' => $produit->id,
                        'code' => $code
                    ]);
                } else {
                    // Mêmes prix → utiliser le produit existant
                    $produit = $produitExistant;
                }
            }
            // ===== NOUVEAU PRODUIT =====
            else {
                $code = $data['code'] ?? 'PRD-' . now()->format('YmdHis') . '-' . random_int(100, 999);
                
                $produit = Produits::create([
                    'nom' => $data['nom'],
                    'code' => $code,
                    'categorie_id' => $data['categorie_id'],
                    'fournisseur_id' => $request->fournisseur_id,
                    'quantite' => 0,
                    'prix_achat' => $prixAchat,
                    'prix_vente' => $prixVente,
                    'prix_gros' => $data['prix_gros'] ?? 0,
                    'etat' => 'disponible',
                    'type' => 'achat',
                ]);
                
                Log::info('Nouveau produit créé:', ['id' => $produit->id, 'nom' => $produit->nom]);
            }

            // ===== AJOUT AU PIVOT =====
            $pivotData[$produit->id] = [
                'quantite' => $quantite,
                'prix_achat' => $prixAchat,
                'prix_vente' => $prixVente,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $totalGeneral += $quantite * $prixAchat;
        }

        // ===== 6. ATTACHEMENT PIVOT =====
        if (!empty($pivotData)) {
            // Utilisez sync() au lieu de attach() pour éviter les problèmes
            $commande->produits()->sync($pivotData, false); // false pour ne pas détacher
            Log::info('Produits synchronisés avec la commande:', [
                'commande_id' => $commande->id,
                'nb_produits' => count($pivotData)
            ]);
        } else {
            throw new \Exception('Aucun produit à attacher à la commande.');
        }

        // ===== 7. MISE À JOUR TOTAL =====
        $commande->montant_total = $totalGeneral;
        $commande->save();
        
        Log::info('Montant total mis à jour:', ['montant' => $totalGeneral]);

        // ===== 8. VALIDATION =====
        DB::commit();

        Log::info('================ COMMANDE CRÉÉE AVEC SUCCÈS ================');

        return redirect()
            ->route('commandes.index')
            ->with('success', 'Commande créée avec succès (N° ' . $commande->numero_commande . ')');

    } catch (Throwable $e) {
        DB::rollBack();
        
        Log::error('ERREUR STORE COMMANDE', [
            'message' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => $e->getTraceAsString(),
        ]);

        return back()
            ->withInput()
            ->with('error', 'Erreur lors de la création: ' . $e->getMessage());
    }
}
    /**
     * Afficher le formulaire d'édition
     */
public function edit($id)
{
    $commande = Commande::with(['produits', 'fournisseur'])->findOrFail($id);
    $produits = Produits::with('categorie')->orderBy('nom')->get();
    $fournisseurs = Fournisseur::orderBy('nom')->get();
    $categories = Categorie::orderBy('nom')->get();

    // Si la collection est vide, transformez-la en array vide pour éviter les erreurs
    $commande->produits = $commande->produits ?? collect([]);
    $produits = $produits ?? collect([]);
    $fournisseurs = $fournisseurs ?? collect([]);
    $categories = $categories ?? collect([]);

    return view('commandes.edit', compact('commande', 'produits', 'fournisseurs', 'categories'));
}


    /**
     * Mettre à jour une commande
     */
public function update(Request $request, $id)
{
    $commande = Commande::with('produits')->findOrFail($id);

    $request->validate([
        'statut' => 'required|in:en_attente,recue,annulee',
        'notes' => 'nullable|string|max:500',
    ]);

    DB::beginTransaction();

    try {
        $ancienStatut = $commande->statut;
        $nouveauStatut = $request->statut;
        
        Log::info('Changement statut commande:', [
            'commande_id' => $commande->id,
            'ancien' => $ancienStatut,
            'nouveau' => $nouveauStatut
        ]);
        
        // ===== LOGIQUE DE STOCK =====
        if ($ancienStatut !== 'recue' && $nouveauStatut === 'recue') {
            // Commande marquée comme "reçue" → augmenter les stocks
            foreach ($commande->produits as $produit) {
                $quantiteCommande = $produit->pivot->quantite;
                $ancienStock = $produit->quantite;
                $produit->quantite += $quantiteCommande;
                $produit->save();
                
                Log::info('Stock augmenté (commande reçue):', [
                    'produit_id' => $produit->id,
                    'nom' => $produit->nom,
                    'ancien_stock' => $ancienStock,
                    'quantite_ajoutee' => $quantiteCommande,
                    'nouveau_stock' => $produit->quantite
                ]);
            }
            
            // Mettre à jour date_reception
            $commande->date_reception = now();
        }
        elseif ($ancienStatut === 'recue' && $nouveauStatut !== 'recue') {
            // Commande n'est plus "reçue" → diminuer les stocks
            foreach ($commande->produits as $produit) {
                $quantiteCommande = $produit->pivot->quantite;
                $ancienStock = $produit->quantite;
                $produit->quantite = max(0, $produit->quantite - $quantiteCommande);
                $produit->save();
                
                Log::info('Stock diminué (commande annulée/attente):', [
                    'produit_id' => $produit->id,
                    'nom' => $produit->nom,
                    'ancien_stock' => $ancienStock,
                    'quantite_retiree' => $quantiteCommande,
                    'nouveau_stock' => $produit->quantite
                ]);
            }
            
            // Si on revient en arrière, effacer date_reception
            $commande->date_reception = null;
        }
        
        // ===== MISE À JOUR COMMANDE =====
        $commande->statut = $nouveauStatut;
        $commande->notes = $request->notes;
        
        $commande->save();

        DB::commit();

        return redirect()
            ->route('commandes.index')
            ->with('success', 'Commande mise à jour avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur mise à jour commande:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return back()
            ->withInput()
            ->with('error', 'Erreur lors de la mise à jour: ' . $e->getMessage());
    }
} 

    /**
     * Supprimer une commande
     */
public function destroy($id)
{
    $commande = Commande::with('produits')->findOrFail($id);

    DB::beginTransaction();

    try {
        // Si la commande était "recue", retirer le stock
        if ($commande->statut === 'recue') {
            foreach ($commande->produits as $produit) {
                $quantiteCommande = $produit->pivot->quantite;
                $ancienStock = $produit->quantite;
                $produit->quantite = max(0, $produit->quantite - $quantiteCommande);
                $produit->save();
                
                Log::info('Stock diminué (suppression commande):', [
                    'produit_id' => $produit->id,
                    'nom' => $produit->nom,
                    'ancien_stock' => $ancienStock,
                    'quantite_retiree' => $quantiteCommande,
                    'nouveau_stock' => $produit->quantite
                ]);
            }
        }
        
        // Détacher les produits
        $commande->produits()->detach();
        
        // Supprimer la commande
        $commande->delete();

        DB::commit();

        return redirect()
            ->route('commandes.index')
            ->with('success', 'Commande supprimée avec succès.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Erreur suppression commande: ' . $e->getMessage());
        
        return back()
            ->with('error', 'Erreur lors de la suppression: ' . $e->getMessage());
    }
}

    /**
     * Imprimer la facture d'une commande
     */
    public function printFacture($id)
    {
        $commande = Commande::with(['fournisseur', 'produits.categorie'])->findOrFail($id);
        return view('commandes.facture', compact('commande'));
    }

    /**
     * Imprimer les tickets produits d'une commande
     */
public function printProductTickets($id)
{
    $commande = Commande::with('produits')->findOrFail($id);
    
    // Vérifiez que la commande a des produits
    if (!$commande->produits || $commande->produits->isEmpty()) {
        return redirect()->route('commandes.show', $id)
            ->with('error', 'Cette commande ne contient aucun produit.');
    }
    
    // Vérifiez que la commande est reçue
    if ($commande->statut !== 'recue') {
        return redirect()->route('commandes.show', $id)
            ->with('warning', 'Les tickets produits ne sont disponibles que pour les commandes reçues.');
    }
    
    return view('commandes.tickets-produits', compact('commande'));
}
    /**
     * Recherche AJAX pour les produits
     */
    /**
 * Recherche AJAX pour les produits
 */
/**
 * Recherche AJAX pour les produits (commande)
 */
public function searchProducts(Request $request)
{
    $query = $request->get('q', ''); // Note: 'q' au lieu de 'query'
    
    if (strlen($query) < 2) {
        return response()->json([]);
    }
    
    $produits = Produits::with('categorie', 'fournisseur')
        ->where(function($q) use ($query) {
            $q->where('nom', 'like', "%{$query}%")
              ->orWhere('code', 'like', "%{$query}%");
        })
        ->limit(10)
        ->get()
        ->map(function($produit) {
            return [
                'id' => $produit->id,
                'nom' => $produit->nom,
                'code' => $produit->code,
                'stock' => $produit->quantite, // Stock réel
                'prix_achat' => $produit->prix_achat,
                'prix_vente' => $produit->prix_vente,
                'prix_gros' => $produit->prix_gros,
                'categorie_id' => $produit->categorie_id,
                'categorie_nom' => $produit->categorie->nom ?? 'Non catégorisé',
                'fournisseur_id' => $produit->fournisseur_id,
                'fournisseur_nom' => $produit->fournisseur->nom ?? 'Non assigné',
            ];
        });
    
    return response()->json($produits);
}

/**
 * Récupérer un produit pour commande
 */
public function getProductForCommande($id)
{
    try {
        Log::info('getProductForCommande appelé avec ID:', ['id' => $id]);
        
        $produit = Produits::with('categorie', 'fournisseur')->find($id);
        
        if (!$produit) {
            Log::warning('Produit non trouvé:', ['id' => $id]);
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }
        
        Log::info('Produit trouvé:', [
            'id' => $produit->id,
            'nom' => $produit->nom,
            'quantite' => $produit->quantite
        ]);
        
        return response()->json([
            'id' => $produit->id,
            'nom' => $produit->nom,
            'code' => $produit->code,
            'stock' => $produit->quantite,
            'prix_achat' => $produit->prix_achat,
            'prix_vente' => $produit->prix_vente,
            'prix_gros' => $produit->prix_gros,
            'categorie_id' => $produit->categorie_id,
            'categorie_nom' => $produit->categorie ? $produit->categorie->nom : 'Non catégorisé',
            'fournisseur_id' => $produit->fournisseur_id,
            'fournisseur_nom' => $produit->fournisseur ? $produit->fournisseur->nom : 'Non assigné',
        ]);
        
    } catch (\Exception $e) {
        Log::error('Erreur dans getProductForCommande:', [
            'id' => $id,
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine()
        ]);
        
        return response()->json([
            'error' => 'Erreur interne du serveur: ' . $e->getMessage()
        ], 500);
    }
}
/**
 * Générer un code produit unique
 */
private function generateUniqueProductCode($prefix = 'PRD')
{
    do {
        $code = $prefix . '-' . now()->format('YmdHis') . '-' . random_int(100, 999);
        $exists = Produits::where('code', $code)->exists();
    } while ($exists);
    
    return $code;
}
}