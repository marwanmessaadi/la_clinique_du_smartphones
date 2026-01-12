@extends('layouts.app')

@section('title', 'Modifier une Réparation')

@section('content')
<style>
    .form-container {
        max-width: 800px;
        margin: 2rem auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .form-header {
        background: #f8f9fa;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
    }

    .form-title {
        font-size: 1.5rem;
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }

    .form-body {
        padding: 2rem;
    }

    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.5rem;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group.full-width {
        grid-column: span 2;
    }

    .form-label {
        display: block;
        margin-bottom: 0.5rem;
        color: #495057;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .form-control {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        transition: all 0.2s ease-in-out;
    }

    .form-control:focus {
        border-color: #80bdff;
        outline: 0;
        box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
    }

    .form-select {
        width: 100%;
        padding: 0.75rem 1rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        appearance: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 1rem center;
        background-size: 16px 12px;
    }

    .form-footer {
        padding: 1.5rem 2rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }

    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 6px;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        color: #fff;
        background-color: #0d6efd;
        border: 1px solid #0a58ca;
    }

    .btn-primary:hover {
        background-color: #0a58ca;
        border-color: #0a53be;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border: 1px solid #5c636a;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #565e64;
    }

    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border: 1px solid transparent;
        border-radius: 6px;
    }

    .alert-danger {
        color: #842029;
        background-color: #f8d7da;
        border-color: #f5c2c7;
    }

    .alert-danger ul {
        margin: 0;
        padding-left: 1.25rem;
    }
</style>

<div class="form-container">
    <div class="form-header">
        <h2 class="form-title">Modifier une réparation</h2>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('reparation.update', $reparation) }}">
        @csrf
        @method('PUT')
        <div class="form-body">
            <div class="form-grid">
                <div class="form-group">
                    <label for="nom" class="form-label">Nom du Client</label>
                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $reparation->nom) }}" required>
                </div>

                <div class="form-group">
                    <label for="produit" class="form-label">Produit</label>
                    <input type="text" class="form-control" id="produit" name="produit" value="{{ old('produit', $reparation->produit) }}" required>
                </div>

                <div class="form-group">
                    <label for="prix" class="form-label">Prix</label>
                    <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="{{ old('prix', $reparation->prix) }}" required min="0">
                </div>

                <div class="form-group">
                    <label for="date_reparation" class="form-label">Date de Réparation</label>
                    <input type="datetime-local" class="form-control" id="date_reparation" name="date_reparation" value="{{ old('date_reparation', $reparation->date_reparation ? $reparation->date_reparation->format('Y-m-d\TH:i') : '') }}">
                </div>

                <div class="form-group">
                    <label for="etat" class="form-label">État</label>
                    <select class="form-select" id="etat" name="etat" required>
                        <option value="en_cours" {{ old('etat', $reparation->etat) == 'en_cours' ? 'selected' : '' }}>En cours</option>
                        <option value="terminee" {{ old('etat', $reparation->etat) == 'terminee' ? 'selected' : '' }}>Terminée</option>
                        <option value="annulee" {{ old('etat', $reparation->etat) == 'annulee' ? 'selected' : '' }}>Annulée</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" class="form-control" id="description" name="description" value="{{ old('description', $reparation->description) }}" placeholder="Description de la réparation (optionnel)">
                </div>
            </div>
        </div>

        <div class="form-footer">
            <a href="{{ route('reparation.index') }}" class="btn btn-secondary">Annuler</a>
            <button type="submit" class="btn btn-primary">Mettre à jour</button>
        </div>
    </form>
</div>
@endsection
