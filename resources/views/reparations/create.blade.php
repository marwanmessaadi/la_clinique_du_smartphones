@extends('layouts.app')
@section('title', 'Nouvelle Réparation')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tools me-2"></i>Nouvelle Réparation
                    </h5>
                </div>
                
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ e($error) }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('reparation.store') }}" method="POST">
                        @csrf
                        
                        <div class="row">
                            <!-- Colonne gauche -->
                            <div class="col-md-6">
                                <!-- Nom du client -->
                                <div class="mb-3">
                                    <label for="nom" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nom du Client *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('nom') is-invalid @enderror" 
                                           id="nom" 
                                           name="nom" 
                                           value="{{ old('nom') }}" 
                                           required
                                           maxlength="255">
                                    @error('nom')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Nom complet du client</div>
                                </div>

                                <!-- Produit -->
                                <div class="mb-3">
                                    <label for="produit" class="form-label">
                                        <i class="fas fa-box me-1"></i>Produit à Réparer *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('produit') is-invalid @enderror" 
                                           id="produit" 
                                           name="produit" 
                                           value="{{ old('produit') }}" 
                                           required
                                           maxlength="255">
                                    @error('produit')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Modèle ou type de produit</div>
                                </div>

                                <!-- Prix -->
                                <div class="mb-3">
                                    <label for="prix" class="form-label">
                                        <i class="fas fa-euro-sign me-1"></i>Prix de la Réparation *
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="form-control @error('prix') is-invalid @enderror" 
                                           id="prix" 
                                           name="prix" 
                                           value="{{ old('prix') }}" 
                                           required>
                                    @error('prix')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Prix en DH (dirhams)</div>
                                </div>
                            </div>

                            <!-- Colonne droite -->
                            <div class="col-md-6">
                                <!-- Date de réparation -->
                                <div class="mb-3">
                                    <label for="date_reparation" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Date de Réparation
                                    </label>
                                    <input type="datetime-local" 
                                           class="form-control @error('date_reparation') is-invalid @enderror" 
                                           id="date_reparation" 
                                           name="date_reparation" 
                                           value="{{ old('date_reparation', now()->format('Y-m-d\TH:i')) }}">
                                    @error('date_reparation')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Date et heure prévues</div>
                                </div>

                                <!-- État -->
                                <div class="mb-3">
                                    <label for="etat" class="form-label">
                                        <i class="fas fa-info-circle me-1"></i>État de la Réparation *
                                    </label>
                                    <select class="form-select @error('etat') is-invalid @enderror" 
                                            id="etat" 
                                            name="etat" 
                                            required>
                                        <option value="en_cours" {{ old('etat') == 'en_cours' ? 'selected' : '' }}>
                                            <span class="badge bg-warning">●</span> En cours
                                        </option>
                                        <option value="terminee" {{ old('etat') == 'terminee' ? 'selected' : '' }}>
                                            <span class="badge bg-success">●</span> Terminée
                                        </option>
                                        <option value="annulee" {{ old('etat') == 'annulee' ? 'selected' : '' }}>
                                            <span class="badge bg-danger">●</span> Annulée
                                        </option>
                                    </select>
                                    @error('etat')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                </div>

                                <!-- Description -->
                                <div class="mb-3">
                                    <label for="description" class="form-label">
                                        <i class="fas fa-file-alt me-1"></i>Description
                                    </label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" 
                                              id="description" 
                                              name="description" 
                                              rows="3"
                                              maxlength="1000">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Détails des travaux à effectuer</div>
                                </div>
                            </div>
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes supplémentaires
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2"
                                      maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ e($message) }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reparation.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Créer la réparation
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
    // Validation côté client
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.querySelector('form');
        const prixInput = document.getElementById('prix');
        
        form.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validation du prix
            if (parseFloat(prixInput.value) < 0) {
                alert('Le prix ne peut pas être négatif');
                prixInput.focus();
                isValid = false;
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
        
        // Formatage automatique du prix
        prixInput.addEventListener('blur', function() {
            if (this.value) {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });
</script>
@endpush