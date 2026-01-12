@extends('layouts.app')
@section('title', 'Nouvelle Catégorie')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Nouvelle Catégorie</h1>
                    <p class="form-subtitle">Ajouter une nouvelle catégorie de produits</p>
                </div>

                <div class="form-body">
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

                    <form action="{{ route('categories.store') }}" method="POST">
                        @csrf

                        <div class="form-group">
                            <label class="form-label" for="nom">Nom de la Catégorie *</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom') }}" placeholder="Entrez le nom de la catégorie" required>
                                <span class="input-group-text">
                                    <i class="fas fa-tag"></i>
                                </span>
                            </div>
                            <small class="form-text">Le nom de la catégorie doit être unique et descriptif</small>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-plus"></i> Créer la catégorie
                            </button>
                            <a href="{{ route('categories.index') }}" class="btn btn-cancel">
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
