@extends('layouts.app')
@section('title', 'Détails Fournisseur')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="profile-card">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h1 class="profile-name">{{ $fournisseur->nom }}</h1>
                    <span class="profile-role">
                        <i class="fas fa-handshake"></i>
                        Fournisseur
                    </span>
                </div>

                <div class="profile-body">
                    <div class="info-section">
                        <h2 class="info-title">
                            <i class="fas fa-building"></i>
                            Informations de l'Entreprise
                        </h2>
                        <div class="info-grid">
                            <div class="info-item">
                                <div class="info-label">Identifiant</div>
                                <div class="info-value">#{{ $fournisseur->id }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Nom de l'entreprise</div>
                                <div class="info-value">{{ $fournisseur->nom }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Date d'ajout</div>
                                <div class="info-value">{{ $fournisseur->created_at->format('d/m/Y à H:i') }}</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Dernière modification</div>
                                <div class="info-value">{{ $fournisseur->updated_at->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h2 class="info-title">
                            <i class="fas fa-address-book"></i>
                            Coordonnées
                        </h2>
                        <div class="contact-info">
                            <div class="contact-details">
                                <div class="contact-item">
                                    <i class="fas fa-envelope contact-icon"></i>
                                    <div class="contact-text">
                                        <strong>Email:</strong><br>
                                        {{ $fournisseur->email ?: 'Non spécifié' }}
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-phone contact-icon"></i>
                                    <div class="contact-text">
                                        <strong>Téléphone:</strong><br>
                                        {{ $fournisseur->telephone ?: 'Non spécifié' }}
                                    </div>
                                </div>
                                <div class="contact-item">
                                    <i class="fas fa-map-marker-alt contact-icon"></i>
                                    <div class="contact-text">
                                        <strong>Adresse:</strong><br>
                                        {{ $fournisseur->adresse ?: 'Non spécifiée' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="info-section">
                        <h2 class="info-title">
                            <i class="fas fa-chart-bar"></i>
                            Statistiques
                        </h2>
                        <div class="supplier-stats">
                            <div class="stats-grid">
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-box"></i>
                                    </div>
                                    <div class="stat-value">{{ $fournisseur->produits_count ?? 0 }}</div>
                                    <div class="stat-label">Produits fournis</div>
                                </div>
                                <div class="stat-item">
                                    <div class="stat-icon">
                                        <i class="fas fa-calendar"></i>
                                    </div>
                                    <div class="stat-value">{{ $fournisseur->created_at->diffForHumans() }}</div>
                                    <div class="stat-label">Partenaire depuis</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('fournisseurs.edit', $fournisseur->id) }}" class="btn-edit">
                            <i class="fas fa-edit"></i>
                            Modifier
                        </a>
                        <a href="{{ route('fournisseurs.index') }}" class="btn-back">
                            <i class="fas fa-arrow-left"></i>
                            Retour à la liste
                        </a>
                        <form action="{{ route('fournisseurs.destroy', $fournisseur->id) }}" method="POST" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-delete"
                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ? Cette action supprimera également tous les produits associés.')">
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
