
<?php $__env->startSection('title', 'Gestion des Fournisseurs'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="page-header">
        <h1 class="page-title">Gestion des Fournisseurs</h1>
        <p class="page-subtitle">Gérez vos partenaires fournisseurs</p>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i>
            <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title  text-white">
                <i class="fas fa-truck"></i>
                Actions Rapides
            </h2>
        </div>
        <div class="card-body ">
            <a href="<?php echo e(route('fournisseurs.create')); ?>" class="btn-add">
                <i class="fas fa-plus"></i>
                Nouveau Fournisseur
            </a>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title text-white">
                <i class="fas fa-search"></i>
                Recherche de Fournisseurs
            </h2>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('fournisseurs.searchByName')); ?>">
                <?php echo csrf_field(); ?>
                <div class="form-group">
                    <label class="form-label" for="query">Rechercher par nom</label>
                    <input type="text" class="form-control" id="query" name="query" placeholder="Entrez le nom du fournisseur" value="<?php echo e(request('query')); ?>">
                </div>
                <button type="submit" class="btn-search">
                    <i class="fas fa-search"></i>
                    Rechercher
                </button>
            </form>
        </div>
    </div>

    <div class="content-card">
        <div class="card-header">
            <h2 class="card-title text-white">
                <i class="fas fa-list"></i>
                Liste des Fournisseurs
            </h2>
        </div>
        <div class="card-body">
            <?php if($fournisseurs->count() > 0): ?>
                <div class="table-container">
                    <table class="fournisseurs-table">
                        <thead>
                            <tr>
                                
                                <th>Nom du Fournisseur</th>
                                <th>Contact</th>
                                <th>Adresse</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    
                                    <td>
                                        <div class="fournisseur-name"><?php echo e($fournisseur->nom); ?></div>
                                    </td>
                                    <td>
                                        <div class="fournisseur-contact">
                                            <div><i class="fas fa-phone"></i> <?php echo e($fournisseur->telephone); ?></div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fournisseur-address"><?php echo e($fournisseur->adresse); ?></div>
                                    </td>
                                    <td>
                                        <div class="action-buttons">
                                            <a href="<?php echo e(route('fournisseurs.show', $fournisseur->id)); ?>" class="btn-view">
                                                <i class="fas fa-eye"></i>
                                                Voir
                                            </a>
                                            <a href="<?php echo e(route('fournisseurs.edit', $fournisseur->id)); ?>" class="btn-edit">
                                                <i class="fas fa-edit"></i>
                                                Modifier
                                            </a>
                                            <form method="POST" action="<?php echo e(route('fournisseurs.destroy', $fournisseur->id)); ?>" class="d-inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce fournisseur ?')">
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
            <?php else: ?>
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-truck"></i>
                    </div>
                    <h3>Aucun fournisseur trouvé</h3>
                    <p>Il n'y a encore aucun fournisseur dans le système.</p>
                    <a href="<?php echo e(route('fournisseurs.create')); ?>" class="btn-add">
                        <i class="fas fa-plus"></i>
                        Créer le premier fournisseur
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/fournisseurs/index.blade.php ENDPATH**/ ?>