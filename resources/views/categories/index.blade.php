@extends('layouts.app')
@section('title', 'Gestion des Catégories')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestion des Catégories</h1>
        <p class="page-subtitle">Organisez vos produits par catégories</p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-tags"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="card-body">
            <a href="{{ route('categories.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                Nouvelle Catégorie
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-search"></i>
                Recherche de Catégories
            </h2>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('categories.searchByName') }}">
                @csrf
                <div class="form-group">
                    <label class="form-label" for="name">Rechercher par nom</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Entrez le nom de la catégorie" value="{{ request('name') }}">
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
            <h2 class="card-title">
                <i class="fas fa-list"></i>
                Liste des Catégories
            </h2>
        </div>
        <div class="card-body">
            @if($categories->count() > 0)
                <div class="table-container">
                    <table class="categories-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom de la Catégorie</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($categories as $categorie)
                                <tr>
                                    <td>
                                        <span class="category-id">#{{ $categorie->id }}</span>
                                    </td>
                                    <td>
                                        <span class="category-name">{{ $categorie->nom }}</span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('categories.show', $categorie->id) }}" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="{{ route('categories.edit', $categorie->id) }}" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <form method="POST" action="{{ route('categories.destroy', $categorie->id) }}" class="d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ?')">
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
                        <i class="fas fa-tags"></i>
                    </div>
                    <h3>Aucune catégorie trouvée</h3>
                    <p>Il n'y a encore aucune catégorie dans le système.</p>
                    <a href="{{ route('categories.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Créer la première catégorie
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
