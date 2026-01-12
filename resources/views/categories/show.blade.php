@extends('layouts.app')
@section('title', 'Détails Catégorie')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-tag"></i>
                    </div>
                    <h1 class="profile-name">{{ $categorie->nom }}</h1>
                    <span class="profile-role">
                        <i class="fas fa-folder"></i>
                        Catégorie
                    </span>
                </div>

                <div class="profile-body">
                    <div class="info-section">
                        <h2 class="info-title">
                            <i class="fas fa-info-circle"></i>
                            Informations de la Catégorie
                        </h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Identifiant</div>
                                <div class="info-value">#{{ $categorie->id }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nom</div>
                                <div class="info-value">{{ $categorie->nom }}</div>
                            </div>
                            @if($categorie->description)
                            <div class="info-item">
                                <div class="info-label">Description</div>
                                <div class="info-value">{{ $categorie->description }}</div>
                            </div>
                            @endif
                            <div class="info-item">
                                <div class="info-label">Date de création</div>
                                <div class="info-value">{{ $categorie->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h2 class="info-title">
                            <i class="fas fa-chart-bar"></i>
                            Statistiques
                        </h2>
                        <div class="category-stats">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="stat-value">{{ $categorie->produits_count ?? 0 }}</div>
                                    <div class="stat-label">Produits</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="stat-value">{{ $categorie->created_at->diffForHumans() }}</div>
                                    <div class="stat-label">Créée</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('categories.edit', $categorie->id) }}" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            Modifier
                        </a>
                        <a href="{{ route('categories.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Retour à la liste
                        </a>
                        <form action="{{ route('categories.destroy', $categorie->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette catégorie ? Cette action supprimera également tous les produits associés.')">
                                <i class="fas fa-trash"></i>
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
