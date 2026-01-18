@extends('layouts.app')
@section('title', 'Détails du Client - ' . $utilisateur->prenom . ' ' . $utilisateur->nom)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <!-- Profil Header -->
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <h1 class="profile-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
                    <p class="profile-role">{{ ucfirst($utilisateur->role) }}</p>
                </div>

                <div class="profile-body">
                    <!-- Informations personnelles -->
                    <div class="info-section">
                        <h3 class="info-title">
                            <i class="fas fa-id-card"></i>Informations personnelles
                        </h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Nom complet</div>
                                <div class="info-value">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Adresse email</div>
                                <div class="info-value">{{ $utilisateur->email }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Téléphone</div>
                                <div class="info-value">{{ $utilisateur->telephone }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Rôle</div>
                                <div class="info-value">{{ ucfirst($utilisateur->role) }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Date d'inscription</div>
                                <div class="info-value">{{ $utilisateur->created_at->format('d/m/Y') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dernière connexion</div>
                                <div class="info-value">{{ $utilisateur->updated_at->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="stats-section">
                        <h4 class="stats-title">
                            <i class="fas fa-chart-bar"></i>Statistiques
                        </h4>
                        <div class="stats-grid">
                            <div class="stat-item">
                                <div class="stat-number">{{ $utilisateur->ventes ? $utilisateur->ventes->count() : 0 }}</div>
                                <div class="stat-label">Achats effectués</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">{{ $utilisateur->ventes ? $utilisateur->ventes->sum('prix_total') : 0 }}DH</div>
                                <div class="stat-label">Total dépensé</div>
                            </div>
                            <div class="stat-item">
                                <div class="stat-number">0</div>
                                <div class="stat-label">Réparations</div>
                            </div>
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="actions-section">
                        <h4 class="actions-title">
                            <i class="fas fa-cogs"></i>Actions
                        </h4>
                        <div class="actions-grid">
                            <a href="{{ route('clients.edit', $utilisateur) }}" class="btn-action btn-edit">
                                <i class="fas fa-edit me-2"></i>Modifier le client
                            </a>
                            <form action="{{ route('clients.destroy', $utilisateur) }}" method="POST" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn-action btn-delete"
                                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client ? Cette action est irréversible.')">
                                    <i class="fas fa-trash me-2"></i>Supprimer le client
                                </button>
                            </form>
                            <a href="{{ route('clients.index') }}" class="btn-action btn-back">
                                <i class="fas fa-arrow-left me-2"></i>Retour à la liste
                            </a>
                        </div>
                    </div>

                    <!-- Activité récente -->
                    @if($utilisateur->ventes && $utilisateur->ventes->count() > 0)
                    <div class="recent-activity">
                        <h4 class="activity-title">
                            <i class="fas fa-history"></i>Derniers achats
                        </h4>
                        @foreach($utilisateur->ventes->take(5) as $vente)
                        <div class="activity-item">
                            <div class="activity-icon">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="activity-content">
                                <p class="activity-text">
                                    Achat de {{ $vente->produit->nom }} ({{ $vente->quantite }} unité{{ $vente->quantite > 1 ? 's' : '' }})
                                </p>
                                <p class="activity-date">{{ $vente->date_vente->format('d/m/Y à H:i') }} - {{ number_format($vente->prix_total, 2) }}DH</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
