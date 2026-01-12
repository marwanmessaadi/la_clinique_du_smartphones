

<?php $__env->startSection('title', 'Tableau de Bord'); ?>

<?php $__env->startSection('content'); ?>
<div class="container">
    <!-- Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success"><?php echo e(session('success')); ?></div>
    <?php endif; ?>
    <?php if($errors->any()): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Tableau de Bord</h1>
        <div>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm mr-2">
                <i class="fas fa-download fa-sm text-white-50"></i> Exporter PDF
            </a>
            <a href="#" class="d-none d-sm-inline-block btn btn-sm btn-success shadow-sm">
                <i class="fas fa-print fa-sm text-white-50"></i> Imprimer
            </a>
        </div>
    </div>

    <!-- Content Row -->
    <div class="row">
        <!-- Ventes du mois -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ventes du mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e(number_format($ventesMois ?? 0, 0, ',', ' ')); ?> DH</div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-success mr-2"><i class="fas fa-arrow-up"></i> 12%</span>
                                <span>vs mois dernier</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-shopping-cart fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Réparations en cours -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Réparations en cours</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($reparations ?? 0); ?></div>
                            <div class="mt-2 mb-0 text-muted text-xs">
                                <span class="text-danger mr-2"><i class="fas fa-arrow-down"></i> 0%</span>
                                <span>vs mois dernier</span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-tools fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nouveaux clients ce mois -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Nouveaux clients ce mois</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo e($nouveauxClients ?? 0); ?></div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lists Row -->
    <div class="row">
        <!-- Products List -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold ">Derniers Produits</h6>
                    <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-sm btn-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>État</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $produits ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($produit->nom); ?></td>
                                        <td><?php echo e(number_format($produit->prix_vente ?? 0, 2, ',', ' ')); ?> DH</td>
                                        <td>
                                            <span class="badge <?php echo e(($produit->quantite ?? 0) > 0 ? 'badge-success' : 'badge-danger'); ?>">
                                                <?php echo e($produit->quantite ?? 0); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge <?php echo e(($produit->etat ?? '') === 'disponible' ? 'badge-success' : 'badge-warning'); ?>">
                                                <?php echo e($produit->etat ?? 'N/A'); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Aucun produit trouvé</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Repairs List -->
        <div class="col-xl-6 col-lg-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold ">Réparations Récentes</h6>
                    <a href="<?php echo e(route('reparation.index')); ?>" class="btn btn-sm btn-info">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Produit</th>
                                    <th>Date</th>
                                    <th>État</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $latestRepairs ?? $repairs ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $repair): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($repair->nom); ?></td>
                                        <td><?php echo e($repair->produit); ?></td>
                                        <td><?php echo e(optional($repair->date_reparation)->format('d/m/Y H:i') ?? ''); ?></td>
                                        <td>
                                            <span class="badge <?php echo e($repair->etat === 'en_cours' ? 'badge-warning' :
                                                ($repair->etat === 'terminee' ? 'badge-success' : 'badge-danger')); ?>">
                                                <?php echo e($repair->etat); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center">Aucune réparation trouvée</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    var salesCanvas = document.getElementById('salesChart');
    if (salesCanvas) {
        var ctx = salesCanvas.getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'Ventes 2023',
                    data: [12000, 19000, 15000, 20000, 21500, 25000],
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                }]
            },
            options: {
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                }
            }
        });
    }

    var topCanvas = document.getElementById('topProductsChart');
    if (topCanvas) {
        var ctx2 = topCanvas.getContext('2d');
        var topProductsChart = new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['iPhone 13', 'Galaxy S22', 'Redmi Note 11', 'Autres'],
                datasets: [{
                    data: [45, 30, 15, 10],
                    backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e'],
                }],
            },
            options: { maintainAspectRatio: false, cutout: '70%' },
        });
    }
</script>
<?php $__env->stopPush(); ?>



<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/index.blade.php ENDPATH**/ ?>