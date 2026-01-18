
<?php $__env->startSection('title', 'Historique des Ventes'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- En-tête -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-shopping-cart"></i> Historique des Ventes
        </h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('pos.vente')); ?>" class="btn btn-success">
                <i class="fas fa-cash-register"></i> POS Vente
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Statistiques -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total des Ventes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(number_format(\App\Models\Vente::sum('prix_total'), 2)); ?> DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventes du Mois
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(number_format(\App\Models\Vente::whereMonth('date_vente', now()->month)->whereYear('date_vente', now()->year)->sum('prix_total'), 2)); ?> DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Aujourd'hui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(number_format(\App\Models\Vente::whereDate('date_vente', today())->sum('prix_total'), 2)); ?> DH
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clock fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nombre de Ventes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(\App\Models\Vente::count()); ?>

                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtres -->
    <div class="card shadow mb-4">
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <input type="text" class="form-control" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="Rechercher...">
                </div>
                <div class="col-md-2">
                    <select class="form-control" name="statut">
                        <option value="">Tous les statuts</option>
                        <option value="finalisee" <?php echo e(request('statut') == 'finalisee' ? 'selected' : ''); ?>>Finalisée</option>
                        <option value="en_cours" <?php echo e(request('statut') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                        <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_debut" 
                           value="<?php echo e(request('date_debut')); ?>" placeholder="Date début">
                </div>
                <div class="col-md-2">
                    <input type="date" class="form-control" name="date_fin" 
                           value="<?php echo e(request('date_fin')); ?>" placeholder="Date fin">
                </div>
                <div class="col-md-3">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search"></i> Filtrer
                    </button>
                    <a href="<?php echo e(route('ventes.index')); ?>" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Réinitialiser
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des ventes -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-white">Liste des Ventes</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>N° Vente</th>
                            <th>Date</th>
                            <th>Produit</th>
                            <th>Client</th>
                            <th>Quantité</th>
                            <th>Prix Unit.</th>
                            <th>Total</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $ventes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><strong><?php echo e($vente->numero_vente); ?></strong></td>
                                <td><?php echo e($vente->date_vente->format('d/m/Y H:i')); ?></td>
                                <td>
                                    <?php if($vente->produit): ?>
                                        <?php echo e($vente->produit->nom); ?>

                                        <br><small class="text-muted"><?php echo e($vente->produit->code); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted">Produit supprimé</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($vente->utilisateur): ?>
                                        <?php echo e($vente->utilisateur->prenom); ?> <?php echo e($vente->utilisateur->nom); ?>

                                    <?php else: ?>
                                        <span class="text-muted">Walk-in</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white"><?php echo e($vente->quantite); ?></span>
                                </td>
                                <td class="text-end"><?php echo e(number_format($vente->prix_unitaire, 2)); ?> DH</td>
                                <td class="text-end">
                                    <strong><?php echo e(number_format($vente->prix_total, 2)); ?> DH</strong>
                                </td>
                                <td>
                                    <?php if($vente->statut === 'finalisee'): ?>
                                        <span class="text-white badge bg-success">Finalisée</span>
                                    <?php elseif($vente->statut === 'en_cours'): ?>
                                        <span class="text-white badge bg-warning">En cours</span>
                                    <?php else: ?>
                                        <span class="text-white badge bg-danger">Annulée</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="<?php echo e(route('ventes.show', $vente->id)); ?>" 
                                           class="btn btn-sm btn-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('ventes.edit', $vente->id)); ?>" 
                                           class="btn btn-sm btn-warning" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if(method_exists($vente, 'printRecu')): ?>
                                        <a href="<?php echo e(route('ventes.recu', $vente->id)); ?>" 
                                           class="btn btn-sm btn-primary" title="Reçu" target="_blank">
                                            <i class="fas fa-receipt"></i>
                                        </a>
                                        <?php endif; ?>
                                        <form action="<?php echo e(route('ventes.destroy', $vente->id)); ?>" 
                                              method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" 
                                                    title="Supprimer"
                                                    onclick="return confirm('Supprimer cette vente ? Le stock sera remis à jour.')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucune vente enregistrée.</p>
                                    <a href="<?php echo e(route('pos.index')); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer la première vente
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($ventes->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($ventes->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/ventes/index.blade.php ENDPATH**/ ?>