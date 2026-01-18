
<?php $__env->startSection('title', 'Tableau de Bord'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.stats-card {
    border-radius: 10px;
    transition: all 0.3s ease;
}
.stats-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.15);
}
.chart-container {
    position: relative;
    height: 300px;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <!-- Messages -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Page Heading -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">
            <i class="fas fa-tachometer-alt"></i> Tableau de Bord
        </h1>
        <div class="d-flex gap-2">
            <a href="<?php echo e(route('pos.vente')); ?>" class="btn btn-success shadow-sm">
                <i class="fas fa-cash-register"></i> Point de Vente
            </a>
            <a href="<?php echo e(route('ventes.index')); ?>" class="btn btn-primary shadow-sm">
                <i class="fas fa-history"></i> Historique
            </a>
        </div>
    </div>

    <!-- Stats Row -->
    <div class="row">
        <!-- Ventes du mois -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ventes du Mois
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(number_format(\App\Models\Vente::whereMonth('date_vente', now()->month)
                                    ->whereYear('date_vente', now()->year)
                                    ->sum('prix_total'), 2)); ?> DH
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">
                                    <?php echo e(\App\Models\Vente::whereMonth('date_vente', now()->month)->count()); ?> transactions
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Ventes Aujourd'hui -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventes Aujourd'hui
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(number_format(\App\Models\Vente::whereDate('date_vente', today())->sum('prix_total'), 2)); ?> DH
                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">
                                    <?php echo e(\App\Models\Vente::whereDate('date_vente', today())->count()); ?> ventes
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Produits en stock -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Produits en Stock
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(\App\Models\Produits::where('quantite', '>', 0)->count()); ?>

                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-danger">
                                    <?php echo e(\App\Models\Produits::where('quantite', '<=', 5)->where('quantite', '>', 0)->count()); ?> 
                                    en rupture proche
                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nouveaux Clients -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stats-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Nouveaux Clients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                <?php echo e(\App\Models\Utilisateur::where('role', 'client')
                                    ->whereMonth('created_at', now()->month)
                                    ->count()); ?>

                            </div>
                            <div class="mt-2 text-xs">
                                <span class="text-muted">
                                    Total: <?php echo e(\App\Models\Utilisateur::where('role', 'client')->count()); ?>

                                </span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-plus fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row">
        <!-- Ventes par Jour (7 derniers jours) -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-line"></i> Ventes des 7 derniers jours
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventesChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Produits -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-chart-pie"></i> Top 5 Produits Vendus
                    </h6>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="topProduitsChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lists Row -->
    <div class="row">
        <!-- Derniers Produits Ajoutés -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-box"></i> Derniers Produits Ajoutés
                    </h6>
                    <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-sm btn-primary">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Prix</th>
                                    <th>Stock</th>
                                    <th>État</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = \App\Models\Produits::orderByDesc('created_at')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($produit->nom); ?></strong>
                                            <br><small class="text-muted"><?php echo e($produit->code); ?></small>
                                        </td>
                                        <td><?php echo e(number_format($produit->prix_vente, 2)); ?> DH</td>
                                        <td>
                                            <span class="text-white badge <?php echo e($produit->quantite > 5 ? 'bg-success' : ($produit->quantite > 0 ? 'bg-warning' : 'bg-danger')); ?>">
                                                <?php echo e($produit->quantite); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-white badge <?php echo e($produit->etat === 'disponible' ? 'bg-success' : 'bg-secondary'); ?>">
                                                <?php echo e(ucfirst($produit->etat)); ?>

                                            </span>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Aucun produit
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Dernières Ventes -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-white">
                        <i class="fas fa-shopping-cart"></i> Dernières Ventes
                    </h6>
                    <a href="<?php echo e(route('ventes.index')); ?>" class="btn btn-sm btn-success">
                        Voir tout
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Produit</th>
                                    <th>Date</th>
                                    <th>Montant</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = \App\Models\Vente::with('produit')->orderByDesc('date_vente')->limit(5)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vente): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><small><?php echo e($vente->numero_vente); ?></small></td>
                                        <td>
                                            <?php echo e($vente->produit ? $vente->produit->nom : 'N/A'); ?>

                                            <br><small class="text-muted">Qté: <?php echo e($vente->quantite); ?></small>
                                        </td>
                                        <td><small><?php echo e($vente->date_vente->format('d/m H:i')); ?></small></td>
                                        <td>
                                            <strong class="text-success"><?php echo e(number_format($vente->prix_total, 2)); ?> DH</strong>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="4" class="text-center py-4 text-muted">
                                            Aucune vente
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Alertes -->
    <?php if(\App\Models\Produits::where('quantite', 0)->count() > 0): ?>
    <div class="row">
        <div class="col-12">
            <div class="alert alert-danger">
                <i class="fas fa-exclamation-triangle"></i>
                <strong>Attention !</strong> 
                <?php echo e(\App\Models\Produits::where('quantite', 0)->count()); ?> produit(s) en rupture de stock.
                <a href="<?php echo e(route('produits.index')); ?>" class="alert-link">Voir les produits</a>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Données pour le graphique des ventes (préparées côté serveur)
    <?php
        $last7DaysData = collect(range(6, 0))->map(function($day) {
            $date = now()->subDays($day);
            return [
                'date' => $date->format('d/m'),
                'total' => \App\Models\Vente::whereDate('date_vente', $date)->sum('prix_total')
            ];
        });
    ?>
    
    const ventesData = <?php echo json_encode($last7DaysData, 15, 512) ?>;

    // Graphique des ventes (7 derniers jours)
    const ventesCtx = document.getElementById('ventesChart');
    if (ventesCtx) {
        new Chart(ventesCtx, {
            type: 'line',
            data: {
                labels: ventesData.map(d => d.date),
                datasets: [{
                    label: 'Ventes (DH)',
                    data: ventesData.map(d => d.total),
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toFixed(0) + ' DH';
                            }
                        }
                    }
                }
            }
        });
    }

    // Données pour top produits
    <?php
        $topProduitsData = \App\Models\Vente::with('produit')
            ->selectRaw('produit_id, SUM(quantite) as total')
            ->groupBy('produit_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get()
            ->map(function($v) {
                return [
                    'nom' => $v->produit ? $v->produit->nom : 'Inconnu',
                    'total' => $v->total
                ];
            });
    ?>

    const topProduitsData = <?php echo json_encode($topProduitsData, 15, 512) ?>;

    // Graphique Top Produits
    const topProduitsCtx = document.getElementById('topProduitsChart');
    if (topProduitsCtx && topProduitsData.length > 0) {
        new Chart(topProduitsCtx, {
            type: 'doughnut',
            data: {
                labels: topProduitsData.map(p => p.nom),
                datasets: [{
                    data: topProduitsData.map(p => p.total),
                    backgroundColor: [
                        'rgba(102, 126, 234, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/index.blade.php ENDPATH**/ ?>