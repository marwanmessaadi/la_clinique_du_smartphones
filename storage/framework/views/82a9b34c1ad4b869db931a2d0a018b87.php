
<?php $__env->startSection('title', 'Nouvelle Réparation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Nouvelle Réparation</h1>
                    <p class="form-subtitle">Créer une nouvelle réparation pour un client</p>
                </div>

                <div class="form-body">
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

                    <form action="<?php echo e(route('reparation.store')); ?>" method="POST">
                        <?php echo csrf_field(); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom du Client *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="nom" name="nom" value="<?php echo e(old('nom')); ?>" placeholder="Nom complet du client" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-user"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Le nom du client qui apporte le produit en réparation</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="produit">Produit à Réparer *</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="produit" name="produit" value="<?php echo e(old('produit')); ?>" placeholder="Modèle ou type de produit" required>
                                        <span class="input-group-text">
                                            <i class="fas fa-tools"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Le produit concerné par la réparation</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prix">Prix de la Réparation *</label>
                                    <div class="input-group">
                                        <input type="number" step="0.01" class="form-control" id="prix" name="prix" value="<?php echo e(old('prix')); ?>" placeholder="0.00" required min="0">
                                        <span class="input-group-text">
                                            <i class="fas fa-euro-sign"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Prix total de la réparation en euros</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="date_reparation">Date de Réparation</label>
                                    <div class="input-group">
                                        <input type="datetime-local" class="form-control" id="date_reparation" name="date_reparation" value="<?php echo e(old('date_reparation', now()->format('Y-m-d\TH:i'))); ?>">
                                        <span class="input-group-text">
                                            <i class="fas fa-calendar"></i>
                                        </span>
                                    </div>
                                    <small class="form-text">Date et heure prévue pour la réparation</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="etat">État de la Réparation *</label>
                                    <select class="form-select" id="etat" name="etat" required>
                                        <option value="en_cours" <?php echo e(old('etat') == 'en_cours' ? 'selected' : ''); ?>>
                                            <span class="status-indicator status-en_cours"></span>
                                            En cours
                                        </option>
                                        <option value="terminee" <?php echo e(old('etat') == 'terminee' ? 'selected' : ''); ?>>
                                            <span class="status-indicator status-terminee"></span>
                                            Terminée
                                        </option>
                                        <option value="annulee" <?php echo e(old('etat') == 'annulee' ? 'selected' : ''); ?>>
                                            <span class="status-indicator status-annulee"></span>
                                            Annulée
                                        </option>
                                    </select>
                                    <small class="form-text">État actuel de la réparation</small>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3" placeholder="Description détaillée de la réparation"><?php echo e(old('description')); ?></textarea>
                                    <small class="form-text">Détails sur les problèmes rencontrés et les travaux effectués</small>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-plus"></i> Créer la réparation
                            </button>
                            <a href="<?php echo e(route('reparation.index')); ?>" class="btn btn-cancel">
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

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/create.blade.php ENDPATH**/ ?>