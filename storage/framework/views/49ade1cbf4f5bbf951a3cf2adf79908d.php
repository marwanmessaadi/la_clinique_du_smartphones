

<?php $__env->startSection('title', 'Liste des Produits'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des Produits</h1>
        <div class="btn-group">
            <a href="<?php echo e(route('produits.create', ['type' => 'achat'])); ?>" class="btn btn-success">
                <i class="fas fa-plus"></i> Produit Achat
            </a>
            <a href="<?php echo e(route('ventes.create')); ?>" class="btn btn-primary">
                <i class="fas fa-plus"></i> Produit Vente
            </a>
        </div>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Produits</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Exporter
                </button>
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
                            <th>Prix de vente</th>
                            <th>Prix de gros</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($produit->nom); ?></td>
                                <td><?php echo e(Str::limit($produit->description, 50)); ?></td>
                                <td class="price-column"><?php echo e(number_format($produit->prix_achat, 2)); ?> DH</td>
                                <td class="price-column"><?php echo e(number_format($produit->prix_vente, 2)); ?> DH</td>
                                <td class="price-column"><?php echo e($produit->prix_gros ? number_format($produit->prix_gros, 2) . ' DH' : '-'); ?></td>
                                <td><?php echo e($produit->categorie?->nom ?? 'Non renseignée'); ?></td>
                                <td>
                                    <span class="badge badge-<?php echo e($produit->type); ?>">
                                        <?php echo e(ucfirst($produit->type)); ?>

                                    </span>
                                </td>
                                <td class="text-center">
                                    <?php if($produit->quantite == 0): ?>
                                        <span class="badge badge-indisponible">Indisponible</span>
                                    <?php else: ?>
                                        <span class="fw-bold"><?php echo e($produit->quantite); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="btn-group-actions">
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?php echo e(route('produits.edit', $produit->id)); ?>" 
                                           class="btn btn-warning" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="<?php echo e(route('produits.show', $produit->id)); ?>" 
                                           class="btn btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="<?php echo e(route('produits.destroy', $produit->id)); ?>" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    Aucun produit trouvé
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function exportToExcel() {
        let table = document.getElementById("productsTable");
        let html = table.outerHTML;
        let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        let downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        downloadLink.href = url;
        downloadLink.download = 'produits.xls';
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    function printTable() {
        window.print();
    }

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/index.blade.php ENDPATH**/ ?>