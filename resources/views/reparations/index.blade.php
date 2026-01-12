@extends('layouts.app')
@section('title', 'Gestion des Réparations')

@section('content')
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestion des Réparations</h1>
        <p class="page-subtitle">Suivez et gérez toutes les réparations de vos clients</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            {{ session('error') }}
        </div>
    @endif

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-tools"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="card-body">
            <a href="{{ route('reparation.create') }}" class="btn-add">
                <i class="fas fa-plus"></i>
                Nouvelle Réparation
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-list"></i>
                Liste des Réparations
            </h2>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <div class="mb-3">
                <form method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, description ou code...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            @if($reparations->count() > 0)
                <div class="table-container">
                    <table class="reparations-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Date de réparation</th>
                                <th>Produit</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reparations as $reparation)
                                <tr>
                                    <td>
                                        <span class="client-name">{{ $reparation->nom }}</span>
                                    </td>
                                    <td>
                                        <span class="reparation-description">{{ Str::limit($reparation->description, 50) }}</span>
                                    </td>
                                    <td>
                                        <span class="price-amount">{{ number_format($reparation->prix, 2) }} €</span>
                                    </td>
                                    <td>
                                        <span class="reparation-date">
                                            {{ $reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'Non définie' }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="product-name">{{ $reparation->produit }}</span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-{{ $reparation->etat }}">
                                            {{ ucfirst(str_replace('_', ' ', $reparation->etat)) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="{{ route('reparation.show', $reparation->id) }}" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="{{ route('reparation.edit', $reparation->id) }}" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <form action="{{ route('reparation.destroy', $reparation->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?')">
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

                @if($reparations->hasPages())
                    <div class="pagination">
                        {{ $reparations->links() }}
                    </div>
                @endif
            @else
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Aucune réparation trouvée</h3>
                    <p>Il n'y a encore aucune réparation dans le système.</p>
                    <a href="{{ route('reparation.create') }}" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Créer la première réparation
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
