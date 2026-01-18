@extends('layouts.app')

@section('title', 'Modifier le Produit')

@section('content')
<div class="container-fluid mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-9 col-xl-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary">
                    <h4 class="text-white mb-0">Modifier le Produit</h4>
                    <small class=" text-white">Mettre à jour les informations du produit</small>
                </div>

                <div class="card-body">
                    {{-- Affichage des erreurs --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('produits.update', $produit->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Nom --}}
                        <div class="mb-3">
                            <label class="form-label">Nom du produit</label>
                            <input type="text" name="nom" class="form-control"
                                value="{{ old('nom', $produit->nom) }}">
                        </div>

                        {{-- Quantité --}}
                        <div class="mb-3">
                            <label class="form-label">Quantité en stock</label>
                            <input type="number" name="quantite" class="form-control"
                                value="{{ old('quantite', $produit->quantite) }}">
                        </div>

                        {{-- Prix Achat --}}
                        <div class="mb-3">
                            <label class="form-label">Prix d'achat</label>
                            <input type="number" step="0.01" name="prix_achat" class="form-control"
                                value="{{ old('prix_achat', $produit->prix_achat) }}">
                        </div>

                        {{-- Prix Vente --}}
                        <div class="mb-3">
                            <label class="form-label">Prix de vente</label>
                            <input type="number" step="0.01" name="prix_vente" class="form-control"
                                value="{{ old('prix_vente', $produit->prix_vente) }}">
                        </div>

                        {{-- Catégorie --}}
                        <div class="mb-3">
                            <label class="form-label">Catégorie</label>
                            <select name="categorie_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                @foreach($categories as $categorie)
                                    <option value="{{ $categorie->id }}"
                                        {{ old('categorie_id', $produit->categorie_id) == $categorie->id ? 'selected' : '' }}>
                                        {{ $categorie->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Fournisseur --}}
                        <div class="mb-3">
                            <label class="form-label">Fournisseur</label>
                            <select name="fournisseur_id" class="form-select">
                                <option value="">-- Sélectionner --</option>
                                @foreach($fournisseurs as $fournisseur)
                                    <option value="{{ $fournisseur->id }}"
                                        {{ old('fournisseur_id', $produit->fournisseur_id) == $fournisseur->id ? 'selected' : '' }}>
                                        {{ $fournisseur->nom }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Date d'achat --}}
                        <div class="mb-3">
                            <label class="form-label">Date d'achat</label>
                            <input type="datetime-local" name="date_achat" class="form-control"
                                value="{{ old('date_achat', $produit->date_achat ? $produit->date_achat->format('Y-m-d\TH:i') : '') }}">
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('produits.index') }}" class="btn btn-secondary">Annuler</a>
                            <button type="submit" class="btn btn-success">Mettre à jour</button>
                        </div>
                    </form>

                    {{-- Historique des commandes liées --}}
                    @if($commandes->count())
                        <hr class="my-4">
                        <h5>Historique des commandes</h5>
                        <table class="table table-bordered mt-2">
                            <thead class="table-light">
                                <tr>
                                    <th>Numéro</th>
                                    <th>Date</th>
                                    <th>Montant Total</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($commandes as $commande)
                                    <tr>
                                        <td>{{ $commande->numero_commande }}</td>
                                        <td>{{ $commande->date_commande->format('d/m/Y') }}</td>
                                        <td>{{ number_format($commande->montant_total, 2) }} DH</td>
                                        <td>{{ ucfirst($commande->statut) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted mt-3">Aucune commande liée à ce produit.</p>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
