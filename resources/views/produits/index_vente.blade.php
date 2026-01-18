@extends('layouts.app')
@section('title', 'Produits de Type Vente')

@section('content')
<div class="container-fluid">
    <!-- Alert d'information -->
    <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
        <i class="fas fa-info-circle"></i> 
        <strong>Note :</strong> Cette page affiche les produits marqués comme "type vente". 
        Pour voir l'historique des transactions de vente, allez sur 
        <a href="{{ route('ventes.index') }}" class="alert-link">Historique des Ventes</a>.
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>

    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tags"></i> Produits de Type "Vente"
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('ventes.index') }}" class="btn btn-success">
                <i class="fas fa-history"></i> Historique des Ventes
            </a>
            <a href="{{ route('produits.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Ajouter un Produit
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                           placeholder="Rechercher...">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="categorie_id">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $categorie)
                            <option value="{{ $categorie->id }}" {{ request('categorie_id') == $categorie->id ? 'selected' : '' }}>
                                {{ $categorie->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="fournisseur_id">
                        <option value="">Tous les fournisseurs</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="etat">
                        <option value="">Tous les états</option>
                        <option value="disponible" {{ request('etat') == 'disponible' ? 'selected' : '' }}>Disponible</option>
                        <option value="indisponible" {{ request('etat') == 'indisponible' ? 'selected' : '' }}>Indisponible</option>
                        <option value="vendu" {{ request('etat') == 'vendu' ? 'selected' : '' }}>Vendu</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="{{ route('produits.vente') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Products Table -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Produits (Type: Vente)</h6>
            <span class="badge bg-info">{{ $produits->total() }} produit(s)</span>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix de vente</th>
                            <th>Quantité</th>
                            <th>État</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($produits as $produit)
                            <tr>
                                <td>
                                    <strong>{{ $produit->nom }}</strong>
                                    @if($produit->code)
                                        <br><small class="text-muted">Code: {{ $produit->code }}</small>
                                    @endif
                                </td>
                                <td>{{ Str::limit($produit->description, 50) }}</td>
                                <td class="text-end">{{ number_format($produit->prix_vente, 2) }} DH</td>
                                <td class="text-center">
                                    <span class="badge {{ $produit->quantite > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $produit->quantite }}
                                    </span>
                                </td>
                                <td>
                                    @if($produit->etat === 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @elseif($produit->etat === 'vendu')
                                        <span class="badge bg-info">Vendu</span>
                                    @else
                                        <span class="badge bg-danger">Indisponible</span>
                                    @endif
                                </td>
                                <td>{{ $produit->categorie ? $produit->categorie->nom : '-' }}</td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('produits.show', $produit->id) }}" 
                                           class="btn btn-info btn-sm" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit->id) }}" 
                                           class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produits.destroy', $produit->id) }}" 
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"
                                                    onclick="return confirm('Supprimer ce produit ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted mb-3">Aucun produit de type "vente" trouvé.</p>
                                    <p class="small text-muted mb-3">
                                        <strong>Note :</strong> Les produits vendus via le POS sont de type "achat".<br>
                                        Pour vendre des produits, utilisez la page 
                                        <a href="{{ route('pos.vente') }}">Point de Vente (POS)</a>.
                                    </p>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <a href="{{ route('produits.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Ajouter un Produit
                                        </a>
                                        <a href="{{ route('ventes.index') }}" class="btn btn-success">
                                            <i class="fas fa-history"></i> Voir l'Historique des Ventes
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($produits->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $produits->links() }}
                </div>
            @endif
        </div>
    </div>

    <!-- Info Box -->
    <div class="alert alert-light border">
        <h5><i class="fas fa-question-circle"></i> Différence entre produits et ventes</h5>
        <ul class="mb-0">
            <li><strong>Produits d'Achat</strong> → Produits que vous achetez pour revendre (visible dans le POS)</li>
            <li><strong>Produits de Vente</strong> → Produits marqués spécifiquement comme "type vente" (cette page)</li>
            <li><strong>Historique des Ventes</strong> → Transactions de vente effectuées via le POS → 
                <a href="{{ route('ventes.index') }}">Voir l'historique</a>
            </li>
        </ul>
    </div>
</div>
@endsection