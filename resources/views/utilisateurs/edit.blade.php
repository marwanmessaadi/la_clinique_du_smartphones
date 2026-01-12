@extends('layouts.app')
@section('title', 'Modifier Utilisateur')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérification de la force du mot de passe
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.querySelector('.password-strength');

    if (passwordInput && strengthIndicator) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            let strength = 0;

            if (password.length >= 8) strength++;
            if (/[a-z]/.test(password)) strength++;
            if (/[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^A-Za-z0-9]/.test(password)) strength++;

            strengthIndicator.className = 'password-strength';

            if (strength <= 2) {
                strengthIndicator.classList.add('weak');
                strengthIndicator.textContent = 'Faible';
            } else if (strength <= 3) {
                strengthIndicator.classList.add('medium');
                strengthIndicator.textContent = 'Moyen';
            } else {
                strengthIndicator.classList.add('strong');
                strengthIndicator.textContent = 'Fort';
            }

            if (password.length === 0) {
                strengthIndicator.textContent = '';
            }
        });
    }

    // Validation des mots de passe identiques
    const confirmPasswordInput = document.getElementById('password_confirmation');

    if (confirmPasswordInput) {
        function validatePasswords() {
            if (passwordInput.value !== confirmPasswordInput.value) {
                confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
            } else {
                confirmPasswordInput.setCustomValidity('');
            }
        }

        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    }
});
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier Utilisateur</h1>
                    <p class="form-subtitle">Mettre à jour les informations de l'utilisateur</p>
                </div>

                <div class="form-body">
                    <div class="current-info">
                        <h6><i class="fas fa-info-circle"></i> Informations actuelles</h6>
                        <p><strong>Utilisateur:</strong> {{ $utilisateur->prenom }} {{ $utilisateur->nom }} | <strong>Email:</strong> {{ $utilisateur->email }}</p>
                    </div>

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

                    <form action="{{ route('utilisateurs.update', $utilisateur->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="{{ old('nom', $utilisateur->nom) }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prenom">Prénom *</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="{{ old('prenom', $utilisateur->prenom) }}" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">Adresse Email *</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $utilisateur->email) }}" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-envelope"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">L'email servira d'identifiant de connexion</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="telephone">Téléphone *</label>
                                    <div class="input-group">
                                        <input type="tel" class="form-control" id="telephone" name="telephone" value="{{ old('telephone', $utilisateur->telephone) }}" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-phone"></i>
                                        </span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="password">Nouveau Mot de Passe</label>
                                    <input type="password" class="form-control" id="password" name="password">
                                    <div class="password-strength"></div>
                                    <small class="form-text">Laisser vide pour conserver le mot de passe actuel</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="password_confirmation">Confirmer le Nouveau Mot de Passe</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="role">Rôle *</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="admin" {{ old('role', $utilisateur->role) == 'admin' ? 'selected' : '' }}>Administrateur</option>
                                        <option value="client" {{ old('role', $utilisateur->role) == 'client' ? 'selected' : '' }}>Client</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour l'utilisateur
                            </button>
                            <a href="{{ route('utilisateurs.index') }}" class="btn btn-cancel">
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
