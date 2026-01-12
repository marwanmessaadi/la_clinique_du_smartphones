@extends('layouts.app')
@section('title', 'Modifier un Produit')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier le Produit</h1>
                    <p class="form-subtitle">Mettez à jour les informations du produit</p>
                </div>

                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('produits.update', $produit->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom du Produit *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="{{ $produit->nom }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3">{{ $produit->description }}</textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="categorie_id">Catégorie</label>
                                    <select class="form-control" id="categorie_id" name="categorie_id">
                                        <option value="">Sélectionner une catégorie</option>
                                        @foreach($categories as $categorie)
                                            <option value="{{ $categorie->id }}" {{ $produit->categorie_id == $categorie->id ? 'selected' : '' }}>
                                                {{ $categorie->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="fournisseur_id">Fournisseur</label>
                                    <select class="form-control" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">Sélectionner un fournisseur</option>
                                        @foreach(\App\Models\Fournisseur::all() as $fournisseur)
                                            <option value="{{ $fournisseur->id }}" {{ $produit->fournisseur_id == $fournisseur->id ? 'selected' : '' }}>
                                                {{ $fournisseur->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="prix_achat">Prix d'achat *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_achat" name="prix_achat" value="{{ $produit->prix_achat }}" step="0.01" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prix_vente">Prix de vente *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_vente" name="prix_vente" value="{{ $produit->prix_vente }}" step="0.01" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prix_gros">Prix de gros</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_gros" name="prix_gros" value="{{ $produit->prix_gros }}" step="0.01">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="quantite">Quantité</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite" value="{{ $produit->quantite }}" min="0">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="type">Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="achat" {{ $produit->type == 'achat' ? 'selected' : '' }}>Achat</option>
                                        <option value="vente" {{ $produit->type == 'vente' ? 'selected' : '' }}>Vente</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="image">Image du produit</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    @if ($produit->image)
                                        <div class="current-image">
                                            <img src="{{ Str::startsWith($produit->image, 'http') ? $produit->image : asset('storage/' . $produit->image) }}" alt="{{ $produit->nom }}" class="image-preview">
                                            <small class="text-muted d-block mt-1">Image actuelle</small>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour le produit
                            </button>
                            <a href="{{ route('produits.show', $produit->id) }}" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
