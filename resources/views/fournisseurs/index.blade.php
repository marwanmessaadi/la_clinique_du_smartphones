@extends('layouts.app')
@section('title', 'Gestion des Fournisseurs')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestion des Fournisseurs</h1>
        <p class="page-subtitle">Gérez vos partenaires fournisseurs</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title  text-white">
                <i class="fas fa-truck"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="card-body ">
            <a href="{{ route('fournisseurs.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                Nouveau Fournisseur
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title text-white">
                <i class="fas fa-search"></i>
                Recherche de Fournisseurs
            </h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('fournisseurs.searchByName') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="query">Rechercher par nom</label>
                    <input type="text" class="form-control" id="query" name="query" placeholder="Entrez le nom du fournisseur" value="{{ request('query') }}">
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title text-white">
                <i class="fas fa-list"></i>
                Liste des Fournisseurs
            </h2>
        </div>
        <div class="card-body">
            @if($fournisseurs->count() > 0)
                <div class="table-container">
                    <table class="fournisseurs-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom du Fournisseur</th>
                                <th>Contact</th>
                                <th>Adresse</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($fournisseurs as $fournisseur)
                                <tr>
                                    <td>
                                        <span class="fournisseur-id">#{{ $fournisseur->id }}</span>
                                    </td>
                                    <td>
                                        <div class="fournisseur-name">{{ $fournisseur->nom }}</div>
                                    </td>
                                    <td>
                                        <div class="fournisseur-contact">
                                            <div><i class="fas fa-envelope"></i> {{ $fournisseur->email }}</div>
                                            <div><i class="fas fa-phone"></i> {{ $fournisseur->telephone }}</div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fournisseur-address">{{ $fournisseur->adresse }}</div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('fournisseurs.show', $fournisseur->id) }}" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <form method="POST" action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
                                                    <i class="fas fa-trash"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Aucun fournisseur trouvé</h3>
                    <p>Il n'y a encore aucun fournisseur dans le système.</p>
                    <a href="{{ route('fournisseurs.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Créer le premier fournisseur
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
