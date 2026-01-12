@extends('layouts.app')
@section('title', 'Produits de Vente')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Produits de Vente</h1>
        <a href="{{ route('ventes.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Produit de Vente
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="filter-section mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par nom, description ou code...">
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
                <div class="row">
                    <div class="col-6">
                        <input type="number" class="form-control" name="prix_min" value="{{ request('prix_min') }}" placeholder="Prix min">
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control" name="prix_max" value="{{ request('prix_max') }}" placeholder="Prix max">
                    </div>
                </div>
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

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold ">Liste des Produits de Vente</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printTable()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable">
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
                                        <br><small class="text-muted">{{ $produit->code }}</small>
                                    @endif
                                </td>
                                <td>{{ Str::limit($produit->description, 50) }}</td>
                                <td class="price-column">{{ number_format($produit->prix_vente, 2) }} €</td>
                                <td>
                                    <span class="badge {{ $produit->quantite > 0 ? 'bg-success' : 'bg-danger' }}">
                                        {{ $produit->quantite }}
                                    </span>
                                </td>
                                <td>
                                    @if($produit->etat === 'disponible')
                                        <span class="badge bg-success">Disponible</span>
                                    @else
                                        <span class="badge bg-danger">Indisponible</span>
                                    @endif
                                </td>
                                <td>{{ $produit->categorie ? $produit->categorie->nom : 'Non catégorisé' }}</td>
                                <td class="btn-group-actions">
                                    <div class="product-actions">
                                        <a href="{{ route('produits.show', $produit->id) }}" class="btn btn-info btn-sm" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produits.edit', $produit->id) }}" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('produits.destroy', $produit->id) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun produit de vente trouvé.</p>
                                    <a href="{{ route('ventes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer le premier produit
                                    </a>
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
</div>

<script>
function printTable() {
    window.print();
}
</script>
@endsection
