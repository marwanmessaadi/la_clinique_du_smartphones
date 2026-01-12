@extends('layouts.app')
@section('title', 'Détails Utilisateur')
@section('content')
<style>
.dashboard-container {
    padding: 2rem;
    max-width: 1200px;
    margin: 0 auto;
}

.profile-card {
    background: #ffffff;
    border-radius: 10px;
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
    overflow: hidden;
    margin-bottom: 2rem;
}

.profile-header {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    color: white;
    padding: 2rem;
    text-align: center;
    position: relative;
}

.profile-avatar {
    width: 80px;
    height: 80px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1rem;
    font-size: 2rem;
    font-weight: 600;
}

.profile-name {
    font-size: 1.8rem;
    font-weight: 600;
    margin: 0;
    text-align: center !important;
}

.profile-role {
    font-size: 1rem;
    opacity: 0.95;
    margin: 0.5rem 0 0 0;
    padding: 0.25rem 0.75rem;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 0.25rem;
    display: inline-block;
    text-align: center !important;
}

.profile-body {
    padding: 2rem;
}

.info-section {
    margin-bottom: 2rem;
}

.info-title {
    font-size: 1.1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.info-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
}

.info-item {
    background: #f8fafc;
    border-radius: 0.375rem;
    padding: 1rem;
    border-left: 3px solid #6f42c1;
}

.info-label {
    font-size: 0.85rem;
    color: #718096;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.25rem;
}

.info-value {
    font-size: 1rem;
    color: #2d3748;
    font-weight: 500;
    margin: 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 0.875rem;
    font-weight: 500;
    line-height: 1.5;
    border-radius: 0.375rem;
    transition: all 0.15s ease-in-out;
    cursor: pointer;
    text-decoration: none;
    border: none;
}

.btn-edit {
    color: #ffffff;
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    border: 1px solid transparent;
}

.btn-edit:hover {
    background: linear-gradient(135deg, #5a32a3 0%, #d6206e 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(111, 66, 193, 0.3);
    color: #ffffff;
}

.btn-back {
    color: #ffffff;
    background-color: #6c757d;
    border: 1px solid transparent;
}

.btn-back:hover {
    background-color: #5a6268;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(108, 117, 125, 0.3);
    color: #ffffff;
}

.btn-delete {
    color: #ffffff;
    background-color: #dc2626;
    border: 1px solid transparent;
}

.btn-delete:hover {
    background-color: #b91c1c;
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(220, 38, 38, 0.3);
    color: #ffffff;
}

.status-badge {
    display: inline-block;
    padding: 0.35em 0.65em;
    border-radius: 0.25rem;
    font-size: 0.75em;
    font-weight: 600;
    text-transform: uppercase;
    line-height: 1;
}

.status-admin {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    color: white;
}

.status-client {
    background-color: #10b981;
    color: #ffffff;
}

.account-info {
    background: #f8fafc;
    border-radius: 0.375rem;
    padding: 1.5rem;
    margin-top: 1rem;
}

.account-title {
    font-size: 1rem;
    font-weight: 600;
    color: #2d3748;
    margin-bottom: 1rem;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.account-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
}

.account-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.account-icon {
    color: #6f42c1;
    font-size: 1.2rem;
}

.account-text {
    font-size: 0.9rem;
    color: #4a5568;
}

.account-text strong {
    color: #2d3748;
}
</style>
<div class="dashboard-container">
    <!-- Search Form -->
    <div class="mb-4">
        <form method="GET" class="d-flex justify-content-center">
            <input type="text" class="form-control me-2" style="max-width: 400px;" name="search" value="{{ request('search') }}" placeholder="Rechercher par nom, prénom ou email...">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>
    </div>

    @foreach ($utilisateurs as $utilisateur)
    <div class="profile-card">
        <div class="profile-header text-center">
            
        
        <div class="profile-avatar">
            {{ strtoupper(substr($utilisateur->prenom, 0, 1) . substr($utilisateur->nom, 0, 1)) }}
        </div>
        <h1 class="profile-name text-center">{{ $utilisateur->prenom }} {{ $utilisateur->nom }}</h1>
        <span class="profile-role text-center">
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
    @endforeach
</div>
@endsection
