<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produits;
use App\Models\Vente;
use App\Models\Utilisateur;
use App\Models\Reparation;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    /**
     * Supprime une réparation.
     */
    public function deleteRepair($id)
    {
        $reparation = Reparation::findOrFail($id);
        $reparation->delete();
        return redirect()->route('pos.reparation')->with('success', 'Réparation supprimée');
    }
    /**
     * Affiche la page POS dédiée à la réparation.
     */
    public function reparationIndex(Request $request)
    {
        // Vous pouvez adapter la logique ici si besoin (reparations, clients, etc.)
        $clients = Utilisateur::where('role', 'client')->get();
        $reparationsQuery = Reparation::orderByDesc('created_at');
        if ($request->filled('search_reparation')) {
            $search = $request->input('search_reparation');
            $reparationsQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('produit', 'like', '%' . $search . '%');
            });
        }
        $reparations = $reparationsQuery->limit(50)->get();
        return view('pos.reparation', compact('reparations', 'clients'));
    }
    /**
     * Affiche la page POS dédiée à la vente.
     */
    public function venteIndex(Request $request)
    {
        // Vous pouvez adapter la logique ici si besoin (produits, clients, etc.)
        $query = Produits::where('type', 'achat')
            ->where('quantite', '>', 0)
            ->with(['categorie', 'fournisseur']);

        if ($request->filled('search_produit')) {
            $search = $request->input('search_produit');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $produits = $query->orderBy('nom')->get();
        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $clients = Utilisateur::where('role', 'client')->get();

        return view('pos.vente', compact('produits', 'categories', 'clients'));
    }
    /**
     * Finalise la vente en espèces (cash).
     */
    public function cash(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);

        // Enregistrement de la vente
        $vente = new \App\Models\Vente();
        $vente->type = 'especes';
        $vente->total = collect($data['panier'])->sum(function($item) { return $item['prix'] * $item['qte']; });
        $vente->user_id = auth()->id();
        $vente->save();

        // Enregistrement des produits vendus (exemple)
        foreach ($data['panier'] as $item) {
            $vente->produits()->attach($item['id'], [
                'quantite' => $item['qte'],
                'prix_vente' => $item['prix']
            ]);
        }

        // Générer l'URL de la facture
        $url = route('ventes.facture', ['vente' => $vente->id]);
        return response()->json(['success' => true, 'facture_url' => $url]);
    }
    /**
     * Enregistre le panier comme brouillon.
     */
    public function draft(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);
        // TODO: Persister le brouillon
        return response()->json(['success' => true]);
    }

    /**
     * Suspend la vente (panier suspendu).
     */
    public function suspend(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);
        // TODO: Persister la vente suspendue
        return response()->json(['success' => true]);
    }

    /**
     * Vente à crédit.
     */
    public function credit(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);
        // TODO: Persister la vente à crédit
        return response()->json(['success' => true]);
    }

    /**
     * Paiement multiple.
     */
    public function multiple(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);
        // TODO: Persister la vente paiement multiple
        return response()->json(['success' => true]);
    }
    /**
     * Enregistre un devis (citation) depuis le POS via AJAX.
     */
    public function quote(Request $request)
    {
        $data = $request->validate([
            'panier' => 'required|array|min:1',
            'panier.*.id' => 'required|exists:produits,id',
            'panier.*.nom' => 'required|string',
            'panier.*.prix' => 'required|numeric',
            'panier.*.qte' => 'required|integer|min:1',
        ]);

        // Ici, on pourrait enregistrer le devis dans une table 'devis' ou 'ventes' avec statut 'devis'.
        // Pour la démo, on ne fait qu'un retour succès.
        // TODO: Persister le devis si besoin.

        return response()->json(['success' => true]);
    }
    /**
     * Affiche la page POS avec les produits disponibles.
     */
    public function index(Request $request)
    {
        $query = Produits::where('type', 'achat')
            ->where('quantite', '>', 0)
            ->with(['categorie', 'fournisseur']);

        // Recherche produit côté serveur
        if ($request->filled('search_produit')) {
            $search = $request->input('search_produit');
            $query->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('code', 'like', '%' . $search . '%');
            });
        }

        // Filtre par catégorie
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }

        $produits = $query->orderBy('nom')->get();
        $categories = \App\Models\Categorie::orderBy('nom')->get();
        $clients = Utilisateur::where('role', 'client')->get();

        // Recherche réparation côté serveur
        $reparationsQuery = Reparation::orderByDesc('created_at');
        if ($request->filled('search_reparation')) {
            $search = $request->input('search_reparation');
            $reparationsQuery->where(function ($q) use ($search) {
                $q->where('nom', 'like', '%' . $search . '%')
                  ->orWhere('description', 'like', '%' . $search . '%')
                  ->orWhere('produit', 'like', '%' . $search . '%');
            });
        }
        $reparations = $reparationsQuery->limit(50)->get();

        return view('pos.index', compact('produits', 'categories', 'clients', 'reparations'));
    }

    /**
     * Traite la vente depuis le POS.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produits' => 'required|array|min:1',
            'produits.*.id' => 'required|exists:produits,id',
            'produits.*.quantite' => 'required|integer|min:1',
            'produits.*.prix_unitaire' => 'required|numeric|min:0',
            'client_id' => 'nullable|exists:utilisateurs,id',
            'notes' => 'nullable|string|max:500',
        ]);

        $total = 0;
        $ventes = [];

        $clientId = $validated['client_id'] ?? null;

        DB::transaction(function () use ($validated, &$total, &$ventes, $clientId) {

            foreach ($validated['produits'] as $item) {
                $produit = Produits::findOrFail($item['id']);

                // Vérifier le stock
                if ($produit->quantite < $item['quantite']) {
                    throw new \Exception("Stock insuffisant pour le produit: {$produit->nom}");
                }

                $prixTotal = $item['prix_unitaire'] * $item['quantite'];
                $total += $prixTotal;

                // Créer la vente
                $vente = Vente::create([
                    'numero_vente' => Vente::generateNumeroVente(),
                    'produit_id' => $item['id'],
                    'utilisateur_id' => $clientId,
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'prix_total' => $prixTotal,
                    'notes' => $validated['notes'] ?? null,
                    'date_vente' => now(),
                    'statut' => 'finalisee',
                ]);

                // Décrémenter le stock
                $produit->decrement('quantite', $item['quantite']);

                // Si la quantité atteint 0, changer le statut à 'vendu'
                if ($produit->quantite <= 0) {
                    $produit->etat = 'vendu';
                    $produit->save();
                }

                $ventes[] = $vente;
            }

            // Ici on pourrait envoyer un email de confirmation ou imprimer un reçu
        });

        // Préparer les données pour le ticket
        $client = $clientId ? Utilisateur::find($clientId) : null;
        $ticketData = [
            'id' => $ventes[0]->numero_vente ?? 'N/A',
            'client' => $client ? $client->prenom . ' ' . $client->nom : 'Anonyme',
            'produits' => array_map(function($vente) {
                return [
                    'nom' => $vente->produit->nom,
                    'quantite' => $vente->quantite,
                    'prix_unitaire' => $vente->prix_unitaire,
                ];
            }, $ventes),
            'total' => $total,
        ];

        return view('ticket', [
            'type' => 'vente',
            'data' => $ticketData
        ]);
    }

    /**
     * Traite la réparation depuis le POS.
     */
    public function storeRepair(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'prix' => 'required|numeric|min:0',
            'produit' => 'required|string|max:255',
            'client_id' => 'nullable|exists:utilisateurs,id',
            'notes' => 'nullable|string|max:500',
            'etat' => 'required|string',
        ]);

        if ($request->filled('id')) {
            // Modification
            $reparation = Reparation::findOrFail($request->id);
            $reparation->update($validated);
        } else {
            // Générer le code de réparation
            $latestReparation = Reparation::latest()->first();
            $number = $latestReparation && $latestReparation->code
                ? intval(substr($latestReparation->code, -6)) + 1
                : 1;
            $validated['code'] = 'REP' . date('Y') . str_pad($number, 6, '0', STR_PAD_LEFT);
            $validated['date_reparation'] = now();
            $reparation = Reparation::create($validated);
        }

        return redirect()->route('pos.reparation')->with('success', 'Réparation enregistrée');
    }
}