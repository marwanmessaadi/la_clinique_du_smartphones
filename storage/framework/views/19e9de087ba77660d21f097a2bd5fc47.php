
<?php $__env->startSection('title', 'Gestion des Réparations'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestion des Réparations</h1>
        <p class="page-subtitle">Suivez et gérez toutes les réparations de vos clients</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger">
            <i class="fas fa-exclamation-triangle"></i>
            <?php echo e(session('error')); ?>

        </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-tools"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="card-body">
            <a href="<?php echo e(route('reparation.create')); ?>" class="btn-add">
                <i class="fas fa-plus"></i>
                Nouvelle Réparation
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title">
                <i class="fas fa-list"></i>
                Liste des Réparations
            </h2>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <div class="mb-3">
                <form method="GET" class="d-flex">
                    <input type="text" class="form-control me-2" name="search" value="<?php echo e(request('search')); ?>" placeholder="Rechercher par nom, description ou code...">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-search"></i> Rechercher
                    </button>
                </form>
            </div>

            <?php if($reparations->count() > 0): ?>
                <div class="table-container">
                    <table class="reparations-table">
                        <thead>
                            <tr>
                                <th>Client</th>
                                <th>Description</th>
                                <th>Prix</th>
                                <th>Date de réparation</th>
                                <th>Produit</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td>
                                        <span class="client-name"><?php echo e($reparation->nom); ?></span>
                                    </td>
                                    <td>
                                        <span class="reparation-description"><?php echo e(Str::limit($reparation->description, 50)); ?></span>
                                    </td>
                                    <td>
                                        <span class="price-amount"><?php echo e(number_format($reparation->prix, 2)); ?> €</span>
                                    </td>
                                    <td>
                                        <span class="reparation-date">
                                            <?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'Non définie'); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <span class="product-name"><?php echo e($reparation->produit); ?></span>
                                    </td>
                                    <td>
                                        <span class="status-badge status-<?php echo e($reparation->etat); ?>">
                                            <?php echo e(ucfirst(str_replace('_', ' ', $reparation->etat))); ?>

                                        </span>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo e(route('reparation.show', $reparation->id)); ?>" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="<?php echo e(route('reparation.edit', $reparation->id)); ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <form action="<?php echo e(route('reparation.destroy', $reparation->id)); ?>" method="POST" style="display: inline;">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?')">
                                                    <i class="fas fa-trash"></i>
                                                    Supprimer
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <?php if($reparations->hasPages()): ?>
                    <div class="pagination">
                        <?php echo e($reparations->links()); ?>

                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-tools"></i>
                    </div>
                    <h3>Aucune réparation trouvée</h3>
                    <p>Il n'y a encore aucune réparation dans le système.</p>
                    <a href="<?php echo e(route('reparation.create')); ?>" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Créer la première réparation
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/index.blade.php ENDPATH**/ ?>