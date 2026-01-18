@extends('layouts.app')
@section('title', 'Modifier Réparation')

@section('content')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">
                        <i class="fas fa-edit me-2"></i>Modifier la Réparation
                    </h5>
                    <small class="text-dark opacity-75">Code: {{ e($reparation->code) }}</small>
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

                    <form action="{{ route('reparation.update', $reparation) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
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
                                           value="{{ old('nom', $reparation->nom) }}" 
                                           required
                                           maxlength="255">
                                    @error('nom')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                </div>

                                <!-- Produit -->
                                <div class="mb-3">
                                    <label for="produit" class="form-label">
                                        <i class="fas fa-box me-1"></i>Produit *
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('produit') is-invalid @enderror" 
                                           id="produit" 
                                           name="produit" 
                                           value="{{ old('produit', $reparation->produit) }}" 
                                           required
                                           maxlength="255">
                                    @error('produit')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                </div>

                                <!-- Prix -->
                                <div class="mb-3">
                                    <label for="prix" class="form-label">
                                        <i class="fas fa-euro-sign me-1"></i>Prix *
                                    </label>
                                    <input type="number" 
                                           step="0.01" 
                                           min="0" 
                                           max="999999.99"
                                           class="form-control @error('prix') is-invalid @enderror" 
                                           id="prix" 
                                           name="prix" 
                                           value="{{ old('prix', $reparation->prix) }}" 
                                           required>
                                    @error('prix')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
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
                                           value="{{ old('date_reparation', $reparation->date_reparation ? $reparation->date_reparation->format('Y-m-d\TH:i') : '') }}">
                                    @error('date_reparation')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                </div>

                                <!-- État -->
                                <div class="mb-3">
                                    <label for="etat" class="form-label">
                                        <i class="fas fa-info-circle me-1"></i>État *
                                    </label>
                                    <select class="form-select @error('etat') is-invalid @enderror" 
                                            id="etat" 
                                            name="etat" 
                                            required>
                                        <option value="en_cours" {{ old('etat', $reparation->etat) == 'en_cours' ? 'selected' : '' }}>
                                            En cours
                                        </option>
                                        <option value="terminee" {{ old('etat', $reparation->etat) == 'terminee' ? 'selected' : '' }}>
                                            Terminée
                                        </option>
                                        <option value="annulee" {{ old('etat', $reparation->etat) == 'annulee' ? 'selected' : '' }}>
                                            Annulée
                                        </option>
                                    </select>
                                    @error('etat')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                </div>

                                <!-- Code -->
                                <div class="mb-3">
                                    <label for="code" class="form-label">
                                        <i class="fas fa-barcode me-1"></i>Code (optionnel)
                                    </label>
                                    <input type="text" 
                                           class="form-control @error('code') is-invalid @enderror" 
                                           id="code" 
                                           name="code" 
                                           value="{{ old('code', $reparation->code) }}"
                                           maxlength="50"
                                           placeholder="Laisser vide pour générer automatiquement">
                                    @error('code')
                                        <div class="invalid-feedback">{{ e($message) }}</div>
                                    @enderror
                                    <div class="form-text">Code unique de la réparation</div>
                                </div>
                            </div>
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
                                      maxlength="1000">{{ old('description', $reparation->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ e($message) }}</div>
                            @enderror
                        </div>

                        <!-- Notes -->
                        <div class="mb-3">
                            <label for="notes" class="form-label">
                                <i class="fas fa-sticky-note me-1"></i>Notes
                            </label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" 
                                      id="notes" 
                                      name="notes" 
                                      rows="2"
                                      maxlength="500">{{ old('notes', $reparation->notes) }}</textarea>
                            @error('notes')
                                <div class="invalid-feedback">{{ e($message) }}</div>
                            @enderror
                        </div>

                        <!-- Boutons -->
                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('reparation.show', $reparation) }}" class="btn btn-secondary">
                                <i class="fas fa-times me-1"></i> Annuler
                            </a>
                            <div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-save me-1"></i> Mettre à jour
                                </button>
                                <a href="{{ route('reparation.index') }}" class="btn btn-outline-secondary ms-2">
                                    <i class="fas fa-arrow-left me-1"></i> Retour
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection