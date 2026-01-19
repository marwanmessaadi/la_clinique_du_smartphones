
<?php $__env->startSection('title', 'Modifier Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-edit me-2"></i>Modifier Commande
            </h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo e(route('commandes.index')); ?>">Commandes</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo e(route('commandes.show', $commande->id)); ?>"><?php echo e($commande->numero_commande); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Modifier</li>
                </ol>
            </nav>
        </div>
        <a href="<?php echo e(route('commandes.show', $commande->id)); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exchange-alt me-2"></i>Changer le statut de la commande
                    </h5>
                </div>
                <div class="card-body">
                    <?php if(session('error')): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <?php echo e(session('error')); ?>

                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('commandes.update', $commande->id)); ?>" method="POST" id="updateForm">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Nouveau statut</label>
                            <select name="statut" class="form-select form-select-lg <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                                <option value="en_attente" <?php echo e(old('statut', $commande->statut) == 'en_attente' ? 'selected' : ''); ?>>
                                    En attente
                                </option>
                                <option value="recue" <?php echo e(old('statut', $commande->statut) == 'recue' ? 'selected' : ''); ?>>
                                    Reçue
                                </option>
                                <option value="annulee" <?php echo e(old('statut', $commande->statut) == 'annulee' ? 'selected' : ''); ?>>
                                    Annulée
                                </option>
                            </select>
                            <?php $__errorArgs = ['statut'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            
                            <div class="form-text">
                                <i class="fas fa-info-circle me-1"></i>
                                Le changement de statut affectera les stocks des produits.
                            </div>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Notes (facultatif)</label>
                            <textarea name="notes" class="form-control <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                      rows="4" placeholder="Notes sur le changement de statut..."><?php echo e(old('notes', $commande->notes)); ?></textarea>
                            <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                        
                        <!-- Informations de la commande -->
                        <div class="card mb-4 border-info">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>Résumé de la commande
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>N° commande:</strong> 
                                            <span class="text-primary"><?php echo e($commande->numero_commande); ?></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Date commande:</strong> 
                                            <?php echo e($commande->date_commande->format('d/m/Y H:i')); ?>

                                        </p>
                                        <p class="mb-2">
                                            <strong>Fournisseur:</strong> 
                                            <?php echo e($commande->fournisseur ? $commande->fournisseur->nom : 'Non spécifié'); ?>

                                        </p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2">
                                            <strong>Statut actuel:</strong> 
                                            <?php if($commande->statut === 'recue'): ?>
                                                <span class="badge bg-success">Reçue</span>
                                            <?php elseif($commande->statut === 'en_attente'): ?>
                                                <span class="badge bg-warning">En attente</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Annulée</span>
                                            <?php endif; ?>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Produits:</strong> 
                                            <span class="badge bg-info"><?php echo e($commande->produits->count()); ?></span>
                                        </p>
                                        <p class="mb-2">
                                            <strong>Montant total:</strong> 
                                            <span class="text-success fw-bold"><?php echo e(number_format($commande->montant_total, 2)); ?> DH</span>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Avertissement changement statut -->
                        <div class="alert alert-warning" id="statusWarning">
                            <h6 class="alert-heading">
                                <i class="fas fa-exclamation-triangle me-2"></i>Attention
                            </h6>
                            <div id="warningMessage">
                                <?php if($commande->statut === 'en_attente'): ?>
                                    En passant à "Reçue", le stock des produits sera augmenté.
                                <?php elseif($commande->statut === 'recue'): ?>
                                    En changeant de "Reçue", le stock des produits sera diminué.
                                <?php else: ?>
                                    En quittant "Annulée", le stock des produits sera ajusté selon le nouveau statut.
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <!-- Liste des produits -->
                        <?php if($commande->produits->count() > 0): ?>
                        <div class="card mb-4">
                            <div class="card-header bg-light">
                                <h6 class="mb-0">
                                    <i class="fas fa-boxes me-2"></i>Produits affectés
                                </h6>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table class="table table-sm mb-0">
                                        <thead>
                                            <tr>
                                                <th class="ps-3">Produit</th>
                                                <th class="text-center">Qté commandée</th>
                                                <th class="text-end">Prix achat</th>
                                                <th class="text-center">Stock actuel</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $commande->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td class="ps-3">
                                                    <div><?php echo e($produit->nom); ?></div>
                                                    <small class="text-muted"><?php echo e($produit->code ?? 'N/A'); ?></small>
                                                </td>
                                                <td class="text-center">
                                                    <?php echo e($produit->pivot->quantite); ?>

                                                </td>
                                                <td class="text-end">
                                                    <?php echo e(number_format($produit->pivot->prix_achat, 2)); ?> DH
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge <?php echo e($produit->quantite > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                                        <?php echo e($produit->quantite); ?>

                                                    </span>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="d-flex justify-content-between mt-4 pt-3 border-top">
                            <a href="<?php echo e(route('commandes.show', $commande->id)); ?>" class="btn btn-secondary">
                                <i class="fas fa-times me-2"></i>Annuler
                            </a>
                            <button type="submit" class="btn btn-primary" id="submitBtn">
                                <i class="fas fa-save me-2"></i>Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const statusSelect = document.querySelector('select[name="statut"]');
    const warningDiv = document.getElementById('statusWarning');
    const warningMessage = document.getElementById('warningMessage');
    const submitBtn = document.getElementById('submitBtn');
    const currentStatus = '<?php echo e($commande->statut); ?>';
    
    function updateWarningMessage(newStatus) {
        let message = '';
        
        if (currentStatus === 'en_attente' && newStatus === 'recue') {
            message = '<strong>→ Stock sera AUGMENTÉ</strong><br>' +
                     'Les produits de cette commande seront ajoutés au stock actuel.';
            warningDiv.className = 'alert alert-success';
        } 
        else if (currentStatus === 'recue' && newStatus !== 'recue') {
            message = '<strong>→ Stock sera DIMINUÉ</strong><br>' +
                     'Les produits de cette commande seront retirés du stock actuel.';
            warningDiv.className = 'alert alert-danger';
        }
        else if (currentStatus === 'annulee' && newStatus === 'recue') {
            message = '<strong>→ Stock sera AUGMENTÉ</strong><br>' +
                     'Les produits de cette commande seront ajoutés au stock actuel.';
            warningDiv.className = 'alert alert-success';
        }
        else if (newStatus === currentStatus) {
            message = 'Aucun changement de stock.';
            warningDiv.className = 'alert alert-info';
        }
        else {
            message = 'Le stock sera ajusté selon le nouveau statut.';
            warningDiv.className = 'alert alert-warning';
        }
        
        warningMessage.innerHTML = `<i class="fas fa-exclamation-triangle me-2"></i>${message}`;
    }
    
    // Initialiser le message
    updateWarningMessage(statusSelect.value);
    
    // Mettre à jour quand le statut change
    statusSelect.addEventListener('change', function() {
        updateWarningMessage(this.value);
    });
    
    // Confirmation avant soumission
    document.getElementById('updateForm').addEventListener('submit', function(e) {
        const newStatus = statusSelect.value;
        
        if (newStatus !== currentStatus) {
            let confirmMessage = '';
            
            if (currentStatus === 'en_attente' && newStatus === 'recue') {
                confirmMessage = 'Êtes-vous sûr de vouloir marquer cette commande comme "Reçue" ?\n' +
                               'Le stock des produits sera augmenté.';
            } 
            else if (currentStatus === 'recue' && newStatus !== 'recue') {
                confirmMessage = 'Êtes-vous sûr de vouloir changer le statut de cette commande ?\n' +
                               'Le stock des produits sera diminué.';
            }
            else {
                confirmMessage = 'Êtes-vous sûr de vouloir changer le statut de cette commande ?';
            }
            
            if (!confirm(confirmMessage)) {
                e.preventDefault();
                return false;
            }
            
            // Désactiver le bouton pour éviter les doubles clics
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Mise à jour...';
        }
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/commandes/show.blade.php ENDPATH**/ ?>