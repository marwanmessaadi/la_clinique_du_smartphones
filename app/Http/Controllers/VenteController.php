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
     * Display a listing of the resource.
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
     * Show the form for creating a new resource.
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
     * Store a newly created resource in storage.
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
        DB::transaction(function () use ($validated, $request, &$vente) {
            $produit = Produits::findOrFail($validated['produit_id']);

            // Vérifier le stock
            if ($produit->quantite < $validated['quantite']) {
                throw new \Exception('Stock insuffisant pour cette vente.');
            }

            // Calculer les prix
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

            // Si la vente est finalisée, décrémenter le stock immédiatement
            if ($validated['statut'] === 'finalisee') {
                $produit->decrement('quantite', $validated['quantite']);
                
                // Si la quantité atteint 0, changer le statut à 'vendu'
                if ($produit->quantite <= 0) {
                    $produit->etat = 'vendu';
                    $produit->save();
                }
            }
        });

        // Si la vente a été finalisée automatiquement, afficher le ticket et imprimer
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

            return view('ticket', ['type' => 'vente', 'data' => $data]);
        }

        return redirect()->route('ventes.index')->with('success', 'Vente créée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        return view('ventes.show', compact('vente'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        $clients = Utilisateur::where('role', 'client')->get();

        return view('ventes.edit', compact('vente', 'clients'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Vente $vente)
    {
        $validated = $request->validate([
            'utilisateur_id' => 'nullable|exists:utilisateurs,id',
            'notes' => 'nullable|string|max:500',
            'statut' => 'required|in:en_cours,finalisee,annulee',
        ]);

        // Si on annule la vente, remettre le stock
        if ($validated['statut'] === 'annulee' && $vente->statut !== 'annulee') {
            $vente->produit->increment('quantite', $vente->quantite);
        }
        // Si on finalise une vente en cours, décrémenter le stock
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
     * Remove the specified resource from storage.
     */
    public function destroy(Vente $vente)
    {
        // Remettre le stock si la vente était finalisée
        if ($vente->statut === 'finalisee') {
            $vente->produit->increment('quantite', $vente->quantite);
        }

        $vente->delete();

        return redirect()->route('ventes.index')->with('success', 'Vente supprimée avec succès.');
    }

    /**
     * Enregistre une vente rapide (méthode existante pour compatibilité).
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
        return view('ventes.recu', compact('vente'));
    }

    public function printFacture(Vente $vente)
    {
        $vente->load(['produit', 'utilisateur']);
        return view('ventes.facture', compact('vente'));
    }
}
