@extends('layouts.app')
@section('title', 'Liste des Réparations')

@push('styles')
<style>
    .table-container {
        background: white;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        overflow: hidden;
    }
    
    .table-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 1.5rem;
    }
    
    .search-form {
        background: #f8f9fa;
        padding: 1.5rem;
        border-bottom: 1px solid #dee2e6;
    }
    
    .stats-card {
        background: white;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    }
    
    .status-badge {
        display: inline-block;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 500;
    }
    
    .status-en_cours { background: #fff3cd; color: #856404; }
    .status-terminee { background: #d4edda; color: #155724; }
    .status-annulee { background: #f8d7da; color: #721c24; }
</style>
@endpush

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-12">
            <div class="table-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h2 class="mb-0">Gestion des Réparations</h2>
                        <p class="mb-0 opacity-75">Suivez toutes les réparations en cours et terminées</p>
                    </div>
                    <a href="{{ route('reparation.create') }}" class="btn btn-light">
                        <i class="fas fa-plus"></i> Nouvelle Réparation
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de recherche -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="search-form">
                <form method="GET" action="{{ route('reparation.index') }}" class="row g-3">
                    <div class="col-md-3">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Rechercher..." value="{{ request('search') }}">
                    </div>
                    <div class="col-md-2">
                        <select name="etat" class="form-select">
                            <option value="">Tous les états</option>
                            <option value="en_cours" {{ request('etat') == 'en_cours' ? 'selected' : '' }}>En cours</option>
                            <option value="terminee" {{ request('etat') == 'terminee' ? 'selected' : '' }}>Terminée</option>
                            <option value="annulee" {{ request('etat') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_debut" class="form-control" 
                               value="{{ request('date_debut') }}" placeholder="Date début">
                    </div>
                    <div class="col-md-2">
                        <input type="date" name="date_fin" class="form-control" 
                               value="{{ request('date_fin') }}" placeholder="Date fin">
                    </div>
                    <div class="col-md-3">
                        <div class="input-group">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-search"></i> Rechercher
                            </button>
                            <a href="{{ route('reparation.index') }}" class="btn btn-secondary">
                                <i class="fas fa-redo"></i> Réinitialiser
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <h6 class="text-muted">Total</h6>
                <h3>{{ $stats['total'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h6 class="text-muted">En cours</h6>
                <h3 class="text-warning">{{ $stats['en_cours'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h6 class="text-muted">Terminées</h6>
                <h3 class="text-success">{{ $stats['terminees'] }}</h3>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <h6 class="text-muted">Montant Total</h6>
                <h3 class="text-primary">{{ number_format($stats['montant_total'], 2) }} DH</h3>
            </div>
        </div>
    </div>

    <!-- Tableau -->
    <div class="row">
        <div class="col-12">
            <div class="table-container">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Date</th>
                                <th>Prix</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($reparations as $reparation)
                                <tr>
                                    <td>
                                        <strong>{{ $reparation->code ?? 'N/A' }}</strong>
                                    </td>
                                    <td>{{ e($reparation->nom) }}</td>
                                    <td>{{ e($reparation->produit) }}</td>
                                    <td>
                                        {{ $reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'N/A' }}
                                    </td>
                                    <td>
                                        <strong>{{ number_format($reparation->prix, 2) }} DH</strong>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $reparation->etat }}">
                                            {{ ucfirst(str_replace('_', ' ', $reparation->etat)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('reparation.show', $reparation) }}" 
                                               class="btn btn-info" title="Voir">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('reparation.edit', $reparation) }}" 
                                               class="btn btn-warning" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(auth()->user()->can('delete', $reparation))
                                            <form action="{{ route('reparation.destroy', $reparation) }}" 
                                                  method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger" 
                                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?')"
                                                        title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <i class="fas fa-inbox fa-2x text-muted mb-2"></i>
                                        <p class="text-muted">Aucune réparation trouvée</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Pagination -->
                @if($reparations->hasPages())
                <div class="card-footer">
                    {{ $reparations->withQueryString()->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection