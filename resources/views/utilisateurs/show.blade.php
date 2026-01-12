@extends('layouts.app')
@section('title', 'Détails Utilisateur')

@section('content')
<div class="dashboard-container">
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-avatar">
                {{ strtoupper(substr($utilisateur->prenom, 0, 1) . substr($utilisateur->nom, 0, 1)) }}
            </div>
            <h1 class="profile-name">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
            <span class="profile-role">
                <i class="fas fa-user-shield"></i>
                {{ $utilisateur->role === 'admin' ? 'Administrateur' : 'Client' }}
            </span>
        </div>

        <div class="profile-body">
            <div class="info-section">
                <h2 class="info-title">
                    <i class="fas fa-user"></i>
                    Informations Personnelles
                </h2>
                <div class="info-grid">
                    <div class="info-item">
                        <div class="info-label">Prénom</div>
                        <div class="info-value">{{ $utilisateur->prenom }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Nom</div>
                        <div class="info-value">{{ $utilisateur->nom }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $utilisateur->email }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">{{ $utilisateur->telephone }}</div>
                    </div>
                </div>
            </div>

            <div class="info-section">
                <h2 class="info-title">
                    <i class="fas fa-cogs"></i>
                    Informations de Compte
                </h2>
                <div class="account-info">
                    <div class="account-details">
                        <div class="account-item">
                            <i class="fas fa-envelope account-icon"></i>
                            <div class="account-text">
                                <strong>Email:</strong> {{ $utilisateur->email }}
                            </div>
                        </div>
                        <div class="account-item">
                            <i class="fas fa-user-tag account-icon"></i>
                            <div class="account-text">
                                <strong>Rôle:</strong>
                                <span class="status-badge {{ $utilisateur->role === 'admin' ? 'status-admin' : 'status-client' }}">
                                    {{ $utilisateur->role === 'admin' ? 'Admin' : 'Client' }}
                                </span>
                            </div>
                        </div>
                        <div class="account-item">
                            <i class="fas fa-calendar account-icon"></i>
                            <div class="account-text">
                                <strong>Créé le:</strong> {{ $utilisateur->created_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                        <div class="account-item">
                            <i class="fas fa-clock account-icon"></i>
                            <div class="account-text">
                                <strong>Dernière modification:</strong> {{ $utilisateur->updated_at->format('d/m/Y à H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="action-buttons">
                <a href="{{ route('utilisateurs.edit', $utilisateur->id) }}" class="btn btn-edit">
                    <i class="fas fa-edit"></i>
                    Modifier
                </a>
                <a href="{{ route('utilisateurs.index') }}" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Retour à la liste
                </a>
                <form action="{{ route('utilisateurs.destroy', $utilisateur->id) }}" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-delete"
                            onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.')">
                        <i class="fas fa-trash"></i>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
