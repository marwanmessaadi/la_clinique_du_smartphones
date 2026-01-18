@extends('layouts.app')
@section('title', 'Historique des Ventes')

@section('content')
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shopping-cart"></i> Historique des Ventes
        </h1>
        <div class="d-flex gap-2">
            <a href="{{ route('pos.vente') }}" class="btn btn-success">
                <i class="fas fa-cash-register"></i> POS Vente
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des Ventes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(\App\Models\Vente::sum('prix_total'), 2) }} DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventes du Mois
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(\App\Models\Vente::whereMonth('date_vente', now()->month)->whereYear('date_vente', now()->year)->sum('prix_total'), 2) }} DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aujourd'hui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ number_format(\App\Models\Vente::whereDate('date_vente', today())->sum('prix_total'), 2) }} DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nombre de Ventes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ \App\Models\Vente::count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" 
                           value="{{ request('search') }}" placeholder="Rechercher...">
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
                    <input type="date" class="form-control" name="date_debut" 
                           value="{{ request('date_debut') }}" placeholder="Date début">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_fin" 
                           value="{{ request('date_fin') }}" placeholder="Date fin">
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
    </div>

    <!-- Liste des ventes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">Liste des Ventes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Quantité</th>
                            <th>Prix Unit.</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ventes as $vente)
                            <tr>
                                <td><strong>{{ $vente->numero_vente }}</strong></td>
                                <td>{{ $vente->date_vente->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($vente->produit)
                                        {{ $vente->produit->nom }}
                                        <br><small class="text-muted">{{ $vente->produit->code }}</small>
                                    @else
                                        <span class="text-muted">Produit supprimé</span>
                                    @endif
                                </td>
                                <td>
                                    @if($vente->utilisateur)
                                        {{ $vente->utilisateur->prenom }} {{ $vente->utilisateur->nom }}
                                    @else
                                        <span class="text-muted">Walk-in</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white">{{ $vente->quantite }}</span>
                                </td>
                                <td class="text-end">{{ number_format($vente->prix_unitaire, 2) }} DH</td>
                                <td class="text-end">
                                    <strong>{{ number_format($vente->prix_total, 2) }} DH</strong>
                                </td>
                                <td>
                                    @if($vente->statut === 'finalisee')
                                        <span class="text-white badge bg-success">Finalisée</span>
                                    @elseif($vente->statut === 'en_cours')
                                        <span class="text-white badge bg-warning">En cours</span>
                                    @else
                                        <span class="text-white badge bg-danger">Annulée</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('ventes.show', $vente->id) }}" 
                                           class="btn btn-sm btn-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('ventes.edit', $vente->id) }}" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if(method_exists($vente, 'printRecu'))
                                        <a href="{{ route('ventes.recu', $vente->id) }}" 
                                           class="btn btn-sm btn-primary" title="Reçu" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        @endif
                                        <form action="{{ route('ventes.destroy', $vente->id) }}" 
                                              method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Supprimer"
                                                    onclick="return confirm('Supprimer cette vente ? Le stock sera remis à jour.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune vente enregistrée.</p>
                                    <a href="{{ route('pos.index') }}" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer la première vente
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($ventes->hasPages())
                <div class="d-flex justify-content-center mt-3">
                    {{ $ventes->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection