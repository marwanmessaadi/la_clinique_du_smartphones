
<?php $__env->startSection('title', 'Modifier Commande'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Modifier Commande: <?php echo e($commande->numero_commande); ?></h1>
        <a href="<?php echo e(route('commandes.show', $commande->id)); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </a>
    </div>

    <?php if(session('error')): ?>
        <div class="alert alert-danger"><?php echo e(session('error')); ?></div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <h6 class="m-0 font-weight-bold">Modifier le statut</h6>
                </div>
                <div class="card-body">
                    <form action="<?php echo e(route('commandes.update', $commande->id)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>
                        
                        <div class="mb-3">
                            <label class="form-label">Statut de la commande</label>
                            <select name="statut" class="form-control" required>
                                <option value="en_attente" <?php echo e($commande->statut == 'en_attente' ? 'selected' : ''); ?>>
                                    En attente
                                </option>
                                <option value="recue" <?php echo e($commande->statut == 'recue' ? 'selected' : ''); ?>>
                                    Reçue
                                </option>
                                <option value="annulee" <?php echo e($commande->statut == 'annulee' ? 'selected' : ''); ?>>
                                    Annulée
                                </option>
                            </select>
                            <div class="form-text">
                                <strong>Attention:</strong> Si vous passez à "Reçue", le stock des produits sera augmenté.
                                Si vous revenez à "En attente" ou "Annulée", le stock sera diminué.
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Notes (facultatif)</label>
                            <textarea name="notes" class="form-control" rows="3"><?php echo e(old('notes', $commande->notes)); ?></textarea>
                        </div>
                        
                        <div class="alert alert-info">
                            <strong>Statut actuel:</strong> <?php echo e($commande->statut); ?><br>
                            <strong>Montant:</strong> <?php echo e(number_format($commande->montant_total, 2)); ?> DH<br>
                            <strong>Produits:</strong> <?php echo e($commande->produits->count()); ?> articles
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="<?php echo e(route('commandes.show', $commande->id)); ?>" class="btn btn-secondary">
                                Annuler
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à jour
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <h6 class="m-0 font-weight-bold">Produits dans la commande</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Qté</th>
                                    <th>Prix</th>
                                    <th>Stock actuel</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $commande->produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($produit->nom); ?></td>
                                    <td><?php echo e($produit->pivot->quantite); ?></td>
                                    <td><?php echo e(number_format($produit->pivot->prix_achat, 2)); ?> DH</td>
                                    <td>
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
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/commandes/edit.blade.php ENDPATH**/ ?>