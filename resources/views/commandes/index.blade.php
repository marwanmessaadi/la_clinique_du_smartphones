@extends('layouts.app')
@section('title', 'Commandes d\'Achat')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart me-2"></i>Commandes d'Achat
            </h1>
            <p class="text-muted mb-0">Gestion des commandes fournisseurs</p>
        </div>
        <a href="{{ route('produits.create') }}" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Nouvelle Commande
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 text-white">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtres
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Recherche</label>
                    <input type="text" class="form-control form-control-sm" name="search" 
                           value="{{ request('search') }}" placeholder="N° commande ou produit...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Fournisseur</label>
                    <select class="form-select form-select-sm" name="fournisseur_id">
                        <option value="">Tous</option>
                        @foreach($fournisseurs as $fournisseur)
                            <option value="{{ $fournisseur->id }}" {{ request('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                {{ $fournisseur->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Statut</label>
                    <select class="form-select form-select-sm" name="statut">
                        <option value="">Tous</option>
                        <option value="en_attente" {{ request('statut') == 'en_attente' ? 'selected' : '' }}>En attente</option>
                        <option value="recue" {{ request('statut') == 'recue' ? 'selected' : '' }}>Reçue</option>
                        <option value="annulee" {{ request('statut') == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date début</label>
                    <input type="date" class="form-control form-control-sm" name="date_debut" 
                           value="{{ request('date_debut') }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date fin</label>
                    <input type="date" class="form-control form-control-sm" name="date_fin" 
                           value="{{ request('date_fin') }}">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card shadow">
        <div class="card-header bg-white py-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Commandes
                </h5>
                <span class="badge bg-secondary">
                    {{ $commandes->total() }} commande(s)
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">N° Commande</th>
                            <th>Date</th>
                            <th>Fournisseur</th>
                            <th class="text-center">Produits</th>
                            <th class="text-center">Qté totale</th>
                            <th class="text-end">Montant</th>
                            <th>Statut</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($commandes as $commande)
                            @php
                                $produitsCount = $commande->produits ? $commande->produits->count() : 0;
                                $quantiteTotale = $commande->produits ? $commande->produits->sum(function($produit) {
                                    return $produit->pivot->quantite;
                                }) : 0;
                            @endphp
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold">{{ $commande->numero_commande }}</div>
                                    <small class="text-muted">#{{ $commande->id }}</small>
                                </td>
                                <td>
                                    <div>{{ $commande->date_commande->format('d/m/Y') }}</div>
                                    <small class="text-muted">{{ $commande->date_commande->format('H:i') }}</small>
                                </td>
                                <td>
                                    @if($commande->fournisseur)
                                        <div class="fw-bold">{{ $commande->fournisseur->nom }}</div>
                                        <small class="text-muted">{{ $commande->fournisseur->telephone ?? '' }}</small>
                                    @else
                                        <span class="text-muted fst-italic">Non spécifié</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white">{{ $produitsCount }}</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary text-white">{{ $quantiteTotale }}</span>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-success">{{ number_format($commande->montant_total, 2) }} DH</div>
                                </td>
                                <td>
                                    @if($commande->statut === 'recue')
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check me-1"></i>Reçue
                                        </span>
                                        @if($commande->date_reception)
                                            <div class="text-muted small">
                                                {{ $commande->date_reception->format('d/m/Y') }}
                                            </div>
                                        @endif
                                    @elseif($commande->statut === 'en_attente')
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-times me-1"></i>Annulée
                                        </span>
                                    @endif
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="{{ route('commandes.show', $commande->id) }}" 
                                           class="btn btn-outline-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('commandes.facture', $commande->id) }}" 
                                           class="btn btn-outline-primary" title="Facture" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        @if($commande->statut === 'recue')
                                            <a href="{{ route('commandes.tickets-produits', $commande->id) }}"
                                               class="btn btn-outline-secondary" title="Tickets produits" target="_blank">
                                                <i class="fas fa-tags"></i>
                                            </a>
                                        @endif
                                        <a href="{{ route('commandes.edit', $commande->id) }}"
                                           class="btn btn-outline-warning" title="Modifier statut">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('commandes.destroy', $commande->id) }}" 
                                              method="POST" class="d-inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="Supprimer"
                                                    onclick="return confirm('Supprimer cette commande ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted fs-5">Aucune commande trouvée</p>
                                        <a href="{{ route('commandes.create') }}" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i>Créer une commande
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($commandes->hasPages())
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        {{ $commandes->links() }}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Confirmation avant suppression
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
@endpush