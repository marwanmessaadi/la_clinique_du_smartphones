
<?php $__env->startSection('title', 'Nouvel Utilisateur'); ?>

<?php $__env->startPush('scripts'); ?>
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

    function validatePasswords() {
        if (passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    if (passwordInput && confirmPasswordInput) {
        passwordInput.addEventListener('input', validatePasswords);
        confirmPasswordInput.addEventListener('input', validatePasswords);
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="dashboard-container">
    <div class="form-card">
        <div class="form-header">
            <h1 class="form-title">Nouvel Utilisateur</h1>
            <p class="form-subtitle">Ajouter un nouvel utilisateur au système</p>
        </div>

        <div class="form-body">
            <?php if($errors->any()): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close">&times;</button>
                    <ul>
                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li><?php echo e($error); ?></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="<?php echo e(route('utilisateurs.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label" for="nom">Nom *</label>
                            <input type="text" class="form-control" id="nom" name="nom" value="<?php echo e(old('nom')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="prenom">Prénom *</label>
                            <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo e(old('prenom')); ?>" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="email">Adresse Email *</label>
                            <div class="input-group">
                                <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
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
                                <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo e(old('telephone')); ?>" required>
                                <span class="input-group-text">
                                    <i class="fas fa-phone"></i>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password">Mot de Passe *</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                            <div class="password-strength"></div>
                            <small class="form-text">Minimum 8 caractères avec majuscules, minuscules et chiffres</small>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="password_confirmation">Confirmer le Mot de Passe *</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="role">Rôle *</label>
                            <select class="form-select" id="role" name="role" required>
                                <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Administrateur</option>
                                <option value="client" <?php echo e(old('role') == 'client' ? 'selected' : ''); ?>>Client</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-user-plus"></i> Créer l'utilisateur
                    </button>
                    <a href="<?php echo e(route('utilisateurs.index')); ?>" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/utilisateurs/create.blade.php ENDPATH**/ ?>