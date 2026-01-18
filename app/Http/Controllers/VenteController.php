<?php

namespace App\Http\Controllers;

use App\Models\Vente;
use App\Models\Produits;
use App\Models\Utilisateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VenteController extends Controller
{
    /**
     * Affiche la liste des ventes.
     */
    public function index(Request $request)
    {
        $query = Vente::with(['produit', 'utilisateur']);

        // Recherche
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filtre par statut
        if ($request->filled('statut')) {
            $query->byStatut($request->statut);
        }

        // Filtre par période
        if ($request->filled('date_debut') && $request->filled('date_fin')) {
            $query->byPeriode($request->date_debut, $request->date_fin);
        }

        $ventes = $query->orderBy('date_vente', 'desc')->paginate(15);

        return view('ventes.index', compact('ventes'));
    }

    /**
     * Affiche le formulaire pour créer une nouvelle vente.
     */
    public function create()
    {
        $produits = Produits::where('type', 'achat')
            ->where('quantite', '>', 0)
            ->orderBy('nom')
            ->get();

        $clients = Utilisateur::where('role', 'client')->get();

        return view('ventes.create', compact('produits', 'clients'));
    }

    /**
     * Enregistre une nouvelle vente et affiche le ticket.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'produit_id' => 'required|exists:produits,id',
            'utilisateur_id' => 'nullable|exists:utilisateurs,id',
            'quantite' => 'required|integer|min:1',
            'prix_unitaire' => 'required|numeric|min:0',
            'statut' => 'required|in:en_cours,finalisee',
            'notes' => 'nullable|string|max:500',
        ]);

        $vente = null;

        DB::transaction(function () use ($validated, &$vente) {
            $produit = Produits::findOrFail($validated['produit_id']);

            // Vérifier le stock
            if ($produit->quantite < $validated['quantite']) {
                throw new \Exception('Stock insuffisant pour cette vente.');
            }

            $prixUnitaire = $validated['prix_unitaire'];
            $prixTotal = $prixUnitaire * $validated['quantite'];

            // Créer la vente
            $vente = Vente::create([
                'numero_vente' => Vente::generateNumeroVente(),
                'produit_id' => $validated['produit_id'],
                'utilisateur_id' => $validated['utilisateur_id'] ?? null,
                'quantite' => $validated['quantite'],
                'prix_unitaire' => $prixUnitaire,
                'prix_total' => $prixTotal,
                'notes' => $validated['notes'] ?? null,
                'date_vente' => now(),
                'statut' => $validated['statut'],
            ]);

            // Décrémenter le stock si la vente est finalisée
            if ($validated['statut'] === 'finalisee') {
                $produit->decrement('quantite', $validated['quantite']);

                if ($produit->quantite <= 0) {
                    $produit->etat = 'vendu';
                    $produit->save();
                }
            }
        });

        // Préparer les données pour le ticket
        if ($vente && $vente->statut === 'finalisee') {
            $vente->load(['produit', 'utilisateur']);
            $data = [
                'id' => $vente->numero_vente,
                'client' => $vente->utilisateur ? ($vente->utilisateur->nom . ' ' . $vente->utilisateur->prenom) : 'Anonyme',
                'produits' => [
                    [
                        'nom' => $vente->produit->nom ?? 'Produit',
                        'quantite' => $vente->quantite,
                        'prix_unitaire' => $vente->prix_unitaire,
                    ]
                ],
                'total' => $vente->prix_total,
            ];

            // Retourner la vue ticket avec la variable $type
            return view('ticket', [
                'type' => 'vente',
                'data' => $data
            ]);
        }

        return redirect()->route('ventes.index')->with('success', 'Vente créée avec succès.');
    }

    /**
     * Affiche les détails d'une vente.
     */
    public function show(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        return view('ventes.show', compact('vente'));
    }

    /**
     * Affiche le formulaire pour éditer une vente.
     */
    public function edit(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        $clients = Utilisateur::where('role', 'client')->get();

        return view('ventes.edit', compact('vente', 'clients'));
    }

    /**
     * Met à jour une vente.
     */
    public function update(Request $request, Vente $vente)
    {
        $validated = $request->validate([
            'utilisateur_id' => 'nullable|exists:utilisateurs,id',
            'notes' => 'nullable|string|max:500',
            'statut' => 'required|in:en_cours,finalisee,annulee',
        ]);

        // Remettre le stock si vente annulée
        if ($validated['statut'] === 'annulee' && $vente->statut !== 'annulee') {
            $vente->produit->increment('quantite', $vente->quantite);
        }
        // Décrémenter stock si finalisation d'une vente en cours
        elseif ($validated['statut'] === 'finalisee' && $vente->statut === 'en_cours') {
            if ($vente->produit->quantite < $vente->quantite) {
                return back()->withErrors(['stock' => 'Stock insuffisant pour finaliser cette vente.']);
            }
            $vente->produit->decrement('quantite', $vente->quantite);
        }

        $vente->update($validated);

        return redirect()->route('ventes.show', $vente)->with('success', 'Vente mise à jour avec succès.');
    }

    /**
     * Supprime une vente.
     */
    public function destroy(Vente $vente)
    {
        if ($vente->statut === 'finalisee') {
            $vente->produit->increment('quantite', $vente->quantite);
        }

        $vente->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée avec succès.');
    }

    /**
     * Méthode pour enregistrement rapide (compatibilité POS).
     */
    public function storeVente(Request $request)
    {
        return $this->store($request);
    }

    /**
     * Générer un reçu pour la vente.
     */
    public function printRecu(Vente $vente)
{
    $vente->load(['produit', 'utilisateur']);

    // Préparer les données pour le ticket
    $data = [
        'id' => $vente->numero_vente,
        'client' => $vente->utilisateur ? ($vente->utilisateur->nom . ' ' . $vente->utilisateur->prenom) : 'Anonyme',
        'produits' => [
            [
                'nom' => $vente->produit->nom ?? 'Produit',
                'quantite' => $vente->quantite,
                'prix_unitaire' => $vente->prix_unitaire,
            ]
        ],
        'total' => $vente->prix_total,
    ];

    return view('ventes.recu', [
        'type' => 'vente', // <- important ! sinon Undefined variable $type
        'data' => $data
    ]);
}

    /**
     * Générer la facture pour la vente.
     */
    public function printFacture(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        return view('ventes.facture', compact('vente'));
    }
}
