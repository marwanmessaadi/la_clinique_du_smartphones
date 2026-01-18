@extends('layouts.app')
@section('title', 'Modifier Commande')

@section('content')
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Modifier Commande
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('commandes.index') }}">Commandes</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('commandes.show', $commande->id) }}">{{ $commande->numero_commande }}</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Changer le statut de la commande
                    </h5>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form action="{{ route('commandes.update', $commande->id) }}" method="POST" id="updateForm">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nouveau statut</label>
                            <select name="statut" class="form-select form-select-lg @error('statut') is-invalid @enderror" required>
                                <option value="en_attente" {{ old('statut', $commande->statut) == 'en_attente' ? 'selected' : '' }}>
                                    En attente
                                </option>
                                <option value="recue" {{ old('statut', $commande->statut) == 'recue' ? 'selected' : '' }}>
                                    Reçue
                                </option>
                                <option value="annulee" {{ old('statut', $commande->statut) == 'annulee' ? 'selected' : '' }}>
                                    Annulée
                                </option>
                            </select>
                            @error('statut')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Le changement de statut affectera les stocks des produits.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Notes (facultatif)</label>
                            <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" 
                                      rows="4" placeholder="Notes sur le changement de statut...">{{ old('notes', $commande->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <!-- Informations de la commande -->
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Résumé de la commande
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>N° commande:</strong> 
                                            <span class="text-primary">{{ $commande->numero_commande }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Date commande:</strong> 
                                            {{ $commande->date_commande->format('d/m/Y H:i') }}
                                        </p>
                                        <p class="mb-2">
                                            <strong>Fournisseur:</strong> 
                                            {{ $commande->fournisseur ? $commande->fournisseur->nom : 'Non spécifié' }}
                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Statut actuel:</strong> 
                                            @if($commande->statut === 'recue')
                                                <span class="badge bg-success">Reçue</span>
                                            @elseif($commande->statut === 'en_attente')
                                                <span class="badge bg-warning">En attente</span>
                                            @else
                                                <span class="badge bg-danger">Annulée</span>
                                            @endif
                                        </p>
                                        <p class="mb-2">
                                            <strong>Produits:</strong> 
                                            <span class="badge bg-info">{{ $commande->produits->count() }}</span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Montant total:</strong> 
                                            <span class="text-success fw-bold">{{ number_format($commande->montant_total, 2) }} DH</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avertissement changement statut -->
                        <div class="alert alert-warning" id="statusWarning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Attention
                            </h6>
                            <div id="warningMessage">
                                @if($commande->statut === 'en_attente')
                                    En passant à "Reçue", le stock des produits sera augmenté.
                                @elseif($commande->statut === 'recue')
                                    En changeant de "Reçue", le stock des produits sera diminué.
                                @else
                                    En quittant "Annulée", le stock des produits sera ajusté selon le nouveau statut.
                                @endif
                            </div>
                        </div>
                        
                        <!-- Liste des produits -->
                        @if($commande->produits->count() > 0)
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-boxes me-2"></i>Produits affectés
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-3">Produit</th>
                                                <th class="text-center">Qté commandée</th>
                                                <th class="text-end">Prix achat</th>
                                                <th class="text-center">Stock actuel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($commande->produits as $produit)
                                            <tr>
                                                <td class="ps-3">
                                                    <div>{{ $produit->nom }}</div>
                                                    <small class="text-muted">{{ $produit->code ?? 'N/A' }}</small>
                                                </td>
                                                <td class="text-center">
                                                    {{ $produit->pivot->quantite }}
                                                </td>
                                                <td class="text-end">
                                                    {{ number_format($produit->pivot->prix_achat, 2) }} DH
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge {{ $produit->quantite > 0 ? 'bg-success' : 'bg-danger' }}">
                                                        {{ $produit->quantite }}
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="{{ route('commandes.show', $commande->id) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="statut"]');
    const warningDiv = document.getElementById('statusWarning');
    const warningMessage = document.getElementById('warningMessage');
    const submitBtn = document.getElementById('submitBtn');
    const currentStatus = '{{ $commande->statut }}';
    
    function updateWarningMessage(newStatus) {
        let message = '';
        
        if (currentStatus === 'en_attente' && newStatus === 'recue') {
            message = '<strong>→ Stock sera AUGMENTÉ</strong><br>' +
                     'Les produits de cette commande seront ajoutés au stock actuel.';
            warningDiv.className = 'alert alert-success';
        } 
        else if (currentStatus === 'recue' && newStatus !== 'recue') {
            message = '<strong>→ Stock sera DIMINUÉ</strong><br>' +
                     'Les produits de cette commande seront retirés du stock actuel.';
            warningDiv.className = 'alert alert-danger';
        }
        else if (currentStatus === 'annulee' && newStatus === 'recue') {
            message = '<strong>→ Stock sera AUGMENTÉ</strong><br>' +
                     'Les produits de cette commande seront ajoutés au stock actuel.';
            warningDiv.className = 'alert alert-success';
        }
        else if (newStatus === currentStatus) {
            message = 'Aucun changement de stock.';
            warningDiv.className = 'alert alert-info';
        }
        else {
            message = 'Le stock sera ajusté selon le nouveau statut.';
            warningDiv.className = 'alert alert-warning';
        }
        
        warningMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    }
    
    // Initialiser le message
    updateWarningMessage(statusSelect.value);
    
    // Mettre à jour quand le statut change
    statusSelect.addEventListener('change', function() {
        updateWarningMessage(this.value);
    });
    
    // Confirmation avant soumission
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        const newStatus = statusSelect.value;
        
        if (newStatus !== currentStatus) {
            let confirmMessage = '';
            
            if (currentStatus === 'en_attente' && newStatus === 'recue') {
                confirmMessage = 'Êtes-vous sûr de vouloir marquer cette commande comme "Reçue" ?\n' +
                               'Le stock des produits sera augmenté.';
            } 
            else if (currentStatus === 'recue' && newStatus !== 'recue') {
                confirmMessage = 'Êtes-vous sûr de vouloir changer le statut de cette commande ?\n' +
                               'Le stock des produits sera diminué.';
            }
            else {
                confirmMessage = 'Êtes-vous sûr de vouloir changer le statut de cette commande ?';
            }
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            
            // Désactiver le bouton pour éviter les doubles clics
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';
        }
    });
});
</script>
@endpush