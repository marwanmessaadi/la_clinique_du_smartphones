
<?php $__env->startSection('title', 'Modifier le Client - ' . $utilisateur->prenom . ' ' . $utilisateur->nom); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Vérification de la force du mot de passe (seulement si un mot de passe est saisi)
    const passwordInput = document.getElementById('password');
    const strengthIndicator = document.querySelector('.password-strength');

    passwordInput.addEventListener('input', function() {
        const password = this.value;
        if (password.length === 0) {
            strengthIndicator.textContent = '';
            return;
        }

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
    });

    // Validation des mots de passe identiques (seulement si un mot de passe est saisi)
    const confirmPasswordInput = document.getElementById('password_confirmation');

    function validatePasswords() {
        if (passwordInput.value && passwordInput.value !== confirmPasswordInput.value) {
            confirmPasswordInput.setCustomValidity('Les mots de passe ne correspondent pas');
        } else {
            confirmPasswordInput.setCustomValidity('');
        }
    }

    passwordInput.addEventListener('input', validatePasswords);
    confirmPasswordInput.addEventListener('input', validatePasswords);
});
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier le Client</h1>
                    <p class="form-subtitle">Mettez à jour les informations du client</p>
                </div>

                <div class="form-body">
                    <!-- Informations actuelles du client -->
                    <div class="client-info">
                        <div class="client-name"><?php echo e($utilisateur->prenom); ?> <?php echo e($utilisateur->nom); ?></div>
                        <div class="client-details">
                            <p><strong>Email:</strong> <?php echo e($utilisateur->email); ?></p>
                            <p><strong>Téléphone:</strong> <?php echo e($utilisateur->telephone); ?></p>
                            <p><strong>Rôle:</strong> <?php echo e(ucfirst($utilisateur->role)); ?></p>
                            <p><strong>Inscrit le:</strong> <?php echo e($utilisateur->created_at->format('d/m/Y')); ?></p>
                        </div>
                    </div>

                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('clients.update', $utilisateur)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo e(old('nom', $utilisateur->nom)); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prenom">Prénom *</label>
                                    <input type="text" class="form-control" id="prenom" name="prenom" value="<?php echo e(old('prenom', $utilisateur->prenom)); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="email">Adresse Email *</label>
                                    <div class="input-group">
                                        <input type="email" class="form-control" id="email" name="email" value="<?php echo e(old('email', $utilisateur->email)); ?>" required>
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
                                        <input type="tel" class="form-control" id="telephone" name="telephone" value="<?php echo e(old('telephone', $utilisateur->telephone)); ?>" required>
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
                                    <label class="form-label" for="role">Rôle</label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="client" <?php echo e($utilisateur->role == 'client' ? 'selected' : ''); ?>>Client</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour le client
                            </button>
                            <a href="<?php echo e(route('clients.show', $utilisateur)); ?>" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php $__env->startPush('scripts'); ?>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/dataTables.bootstrap4.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#dataTable').DataTable({
                "order": [[ 0, "desc" ]]
            });
        });
    </script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/clients/edit.blade.php ENDPATH**/ ?>