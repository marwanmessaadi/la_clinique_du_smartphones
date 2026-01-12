@extends('layouts.app')
@section('title', 'Gestion des Ventes')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Gestion des Ventes</h1>
        <a href="{{ route('ventes.create') }}" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouvelle Vente
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Filters -->
    <div class="filter-section">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}"
                       placeholder="Rechercher par numéro, produit ou client...">
            </div>
            <div class="col-md-2">
                <select class="form-control" name="statut">
                    <option value="">Tous les statuts</option>
                    <option value="finalisee" {{ request('statut') == 'finalisee' ? 'selected' : '' }}>Finalisée</option>
                    <option value="en_cours" {{ request('statut') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                    <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_debut" value="{{ request('date_debut') }}">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_fin" value="{{ request('date_fin') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="{{ route('ventes.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Sales Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des produits Ventes</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="window.print()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="salesTable">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Quantité</th>
                            <th>Prix Total</th>
                            <th>Statut</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes as $vente)
                            <tr>
                                <td>
                                    <strong>{{ $vente->numero_vente }}</strong>
                                </td>
                                <td>
                                    {{ $vente->produit->nom }}
                                    @if($vente->produit->code)
                                        <br><small class="text-muted">{{ $vente->produit->code }}</small>
                                    @endif
                                </td>
                                <td>
                                    @if($vente->utilisateur)
                                        {{ $vente->utilisateur->prenom }} {{ $vente->utilisateur->nom }}
                                    @else
                                        <span class="text-muted">Client anonyme</span>
                                    @endif
                                </td>
                                <td>{{ $vente->quantite }}</td>
                                <td class="price-column">{{ number_format($vente->prix_total, 2) }} €</td>
                                <td>
                                    @if($vente->statut === 'finalisee')
                                        <span class="badge badge-success">Finalisée</span>
                                    @elseif($vente->statut === 'en_cours')
                                        <span class="badge badge-warning">En cours</span>
                                    @else
                                        <span class="badge badge-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                <td class="sales-actions">
                                    <div class="btn-group d-flex flex-wrap gap-1" role="group">
                                        <a href="{{ route('ventes.show', $vente) }}" class="btn btn-info btn-sm" title="Voir détails" style="min-width: 90px; font-weight: 500;">
                                            <i class="fas fa-eye"></i> <span class="d-none d-md-inline">Détails</span>
                                        </a>
                                        <a href="{{ route('ventes.edit', $vente) }}" class="btn btn-warning btn-sm" title="Modifier" style="min-width: 90px; font-weight: 500;">
                                            <i class="fas fa-edit"></i> <span class="d-none d-md-inline">Modifier</span>
                                        </a>
                                        <a href="{{ route('ventes.recu', $vente) }}" class="btn btn-success btn-sm" title="Imprimer reçu" target="_blank" style="min-width: 90px; font-weight: 500;">
                                            <i class="fas fa-print"></i> <span class="d-none d-md-inline">Reçu</span>
                                        </a>
                                        <form action="{{ route('ventes.destroy', $vente) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer" style="min-width: 90px; font-weight: 500;"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette vente ?')">
                                                <i class="fas fa-trash"></i> <span class="d-none d-md-inline">Supprimer</span>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune vente trouvée.</p>
                                    <a href="{{ route('ventes.create') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer la première vente
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($ventes->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $ventes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
