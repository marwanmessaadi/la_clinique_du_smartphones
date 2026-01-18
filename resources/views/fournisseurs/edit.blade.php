@extends('layouts.app')
@section('title', 'Modifier Fournisseur')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier Fournisseur</h1>
                    <p class="form-subtitle">Mettre à jour les informations du fournisseur</p>
                </div>

                <div class="form-body">
                    <div class="current-info">
                        <h6><i class="fas fa-info-circle"></i> Informations actuelles</h6>
                        <p><strong>ID:</strong> #{{ $fournisseur->id }} | <strong>Nom:</strong> {{ $fournisseur->nom }}</p>
                    </div>

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

                    <form action="{{ route('fournisseurs.update', $fournisseur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom du Fournisseur *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $fournisseur->nom) }}" placeholder="Entrez le nom du fournisseur" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-building"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Le nom complet de l'entreprise fournisseur</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="telephone">Téléphone</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $fournisseur->telephone) }}" placeholder="01 23 45 67 89">
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Numéro de téléphone de contact</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="adresse">Adresse</label>
                                    <textarea class="form-control" id="adresse" name="adresse" placeholder="Adresse complète du fournisseur">{{ old('adresse', $fournisseur->adresse) }}</textarea>
                                    <small class="form-text">Adresse postale complète avec code postal et ville</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour le fournisseur
                            </button>
                            <a href="{{ route('fournisseurs.index') }}" class="btn btn-cancel">
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
