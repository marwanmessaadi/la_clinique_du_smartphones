
<?php $__env->startSection('title', 'Produits d\'Achat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Produits d'Achat</h1>
        <a href="<?php echo e(route('produits.create', ['type' => 'achat'])); ?>" class="btn btn-success">
            <i class="fas fa-plus"></i> Nouveau Produit d'Achat
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Filters -->
    <div class="filter-section mb-4">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <input type="text" class="form-control" name="search" value="<?php echo e(request('search')); ?>"
                       placeholder="Rechercher par nom, description ou code...">
            </div>
            <div class="col-md-2">
                <select class="form-control" name="categorie_id">
                    <option value="">Toutes les catégories</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($categorie->id); ?>" <?php echo e(request('categorie_id') == $categorie->id ? 'selected' : ''); ?>>
                            <?php echo e($categorie->nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="fournisseur_id">
                    <option value="">Tous les fournisseurs</option>
                    <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(request('fournisseur_id') == $fournisseur->id ? 'selected' : ''); ?>>
                            <?php echo e($fournisseur->nom); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>
            <div class="col-md-2">
                <select class="form-control" name="etat">
                    <option value="">Tous les états</option>
                    <option value="disponible" <?php echo e(request('etat') == 'disponible' ? 'selected' : ''); ?>>Disponible</option>
                    <option value="indisponible" <?php echo e(request('etat') == 'indisponible' ? 'selected' : ''); ?>>Indisponible</option>
                    <option value="vendu" <?php echo e(request('etat') == 'vendu' ? 'selected' : ''); ?>>Vendu</option>
                </select>
            </div>
            <div class="col-md-3">
                <div class="row">
                    <div class="col-6">
                        <input type="number" class="form-control" name="prix_min" value="<?php echo e(request('prix_min')); ?>" placeholder="Prix min">
                    </div>
                    <div class="col-6">
                        <input type="number" class="form-control" name="prix_max" value="<?php echo e(request('prix_max')); ?>" placeholder="Prix max">
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary me-2">
                    <i class="fas fa-search"></i> Filtrer
                </button>
                <a href="<?php echo e(route('produits.achat')); ?>" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Réinitialiser
                </a>
            </div>
        </form>
    </div>

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Produits d'Achat</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printTable()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix d'achat</th>
                            <th>Quantité</th>
                            <th>État</th>
                            <th>Catégorie</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td>
                                    <strong><?php echo e($produit->nom); ?></strong>
                                    <?php if($produit->code): ?>
                                        <br><small class="text-muted"><?php echo e($produit->code); ?></small>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e(Str::limit($produit->description, 50)); ?></td>
                                <td class="price-column"><?php echo e(number_format($produit->prix_achat, 2)); ?> €</td>
                                <td>
                                    <span class="badge <?php echo e($produit->quantite > 0 ? 'bg-success' : 'bg-danger'); ?>">
                                        <?php echo e($produit->quantite); ?>

                                    </span>
                                </td>
                                <td>
                                    <?php if($produit->etat === 'disponible'): ?>
                                        <span class="badge bg-success">Disponible</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Indisponible</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($produit->categorie ? $produit->categorie->nom : 'Non catégorisé'); ?></td>
                                <td class="btn-group-actions">
                                    <div class="product-actions">
                                        <a href="<?php echo e(route('produits.show', $produit->id)); ?>" class="btn btn-info btn-sm" title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('produits.edit', $produit->id)); ?>" class="btn btn-warning btn-sm" title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('produits.destroy', $produit->id)); ?>" method="POST" style="display:inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger btn-sm" title="Supprimer"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">Aucun produit d'achat trouvé.</p>
                                    <a href="<?php echo e(route('produits.create', ['type' => 'achat'])); ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Créer le premier produit
                                    </a>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php if($produits->hasPages()): ?>
                <div class="d-flex justify-content-center mt-3">
                    <?php echo e($produits->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function printTable() {
    window.print();
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/index_achat.blade.php ENDPATH**/ ?>