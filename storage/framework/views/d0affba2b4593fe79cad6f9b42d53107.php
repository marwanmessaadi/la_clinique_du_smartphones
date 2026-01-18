<?php $__env->startSection('title', 'Commandes d\'Achat'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid px-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0 text-gray-800">
                <i class="fas fa-shopping-cart me-2"></i>Commandes d'Achat
            </h1>
            <p class="text-muted mb-0">Gestion des commandes fournisseurs</p>
        </div>
        <a href="<?php echo e(route('produits.create')); ?>" class="btn btn-primary">
            <i class="fas fa-plus-circle me-2"></i>Nouvelle Commande
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-white py-3 text-white">
            <h5 class="mb-0">
                <i class="fas fa-filter me-2"></i>Filtres
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" class="row g-3">
                <div class="col-md-3">
                    <label class="form-label small fw-bold">Recherche</label>
                    <input type="text" class="form-control form-control-sm" name="search" 
                           value="<?php echo e(request('search')); ?>" placeholder="N° commande ou produit...">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Fournisseur</label>
                    <select class="form-select form-select-sm" name="fournisseur_id">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $fournisseurs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($fournisseur->id); ?>" <?php echo e(request('fournisseur_id') == $fournisseur->id ? 'selected' : ''); ?>>
                                <?php echo e($fournisseur->nom); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Statut</label>
                    <select class="form-select form-select-sm" name="statut">
                        <option value="">Tous</option>
                        <option value="en_attente" <?php echo e(request('statut') == 'en_attente' ? 'selected' : ''); ?>>En attente</option>
                        <option value="recue" <?php echo e(request('statut') == 'recue' ? 'selected' : ''); ?>>Reçue</option>
                        <option value="annulee" <?php echo e(request('statut') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date début</label>
                    <input type="date" class="form-control form-control-sm" name="date_debut" 
                           value="<?php echo e(request('date_debut')); ?>">
                </div>
                <div class="col-md-2">
                    <label class="form-label small fw-bold">Date fin</label>
                    <input type="date" class="form-control form-control-sm" name="date_fin" 
                           value="<?php echo e(request('date_fin')); ?>">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des commandes -->
    <div class="card shadow">
        <div class="card-header bg-white py-3 text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-list me-2"></i>Liste des Commandes
                </h5>
                <span class="badge bg-secondary">
                    <?php echo e($commandes->total()); ?> commande(s)
                </span>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">N° Commande</th>
                            <th>Date</th>
                            <th>Fournisseur</th>
                            <th class="text-center">Produits</th>
                            <th class="text-center">Qté totale</th>
                            <th class="text-end">Montant</th>
                            <th>Statut</th>
                            <th class="text-center pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__empty_1 = true; $__currentLoopData = $commandes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $commande): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <?php
                                $produitsCount = $commande->produits ? $commande->produits->count() : 0;
                                $quantiteTotale = $commande->produits ? $commande->produits->sum(function($produit) {
                                    return $produit->pivot->quantite;
                                }) : 0;
                            ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="fw-bold"><?php echo e($commande->numero_commande); ?></div>
                                    <small class="text-muted">#<?php echo e($commande->id); ?></small>
                                </td>
                                <td>
                                    <div><?php echo e($commande->date_commande->format('d/m/Y')); ?></div>
                                    <small class="text-muted"><?php echo e($commande->date_commande->format('H:i')); ?></small>
                                </td>
                                <td>
                                    <?php if($commande->fournisseur): ?>
                                        <div class="fw-bold"><?php echo e($commande->fournisseur->nom); ?></div>
                                        <small class="text-muted"><?php echo e($commande->fournisseur->telephone ?? ''); ?></small>
                                    <?php else: ?>
                                        <span class="text-muted fst-italic">Non spécifié</span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-info text-white"><?php echo e($produitsCount); ?></span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary text-white"><?php echo e($quantiteTotale); ?></span>
                                </td>
                                <td class="text-end">
                                    <div class="fw-bold text-success"><?php echo e(number_format($commande->montant_total, 2)); ?> DH</div>
                                </td>
                                <td>
                                    <?php if($commande->statut === 'recue'): ?>
                                        <span class="badge bg-success text-white">
                                            <i class="fas fa-check me-1"></i>Reçue
                                        </span>
                                        <?php if($commande->date_reception): ?>
                                            <div class="text-muted small">
                                                <?php echo e($commande->date_reception->format('d/m/Y')); ?>

                                            </div>
                                        <?php endif; ?>
                                    <?php elseif($commande->statut === 'en_attente'): ?>
                                        <span class="badge bg-warning text-dark">
                                            <i class="fas fa-clock me-1"></i>En attente
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-danger text-white">
                                            <i class="fas fa-times me-1"></i>Annulée
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center pe-4">
                                    <div class="btn-group btn-group-sm" role="group">
                                        <a href="<?php echo e(route('commandes.show', $commande->id)); ?>" 
                                           class="btn btn-outline-info" title="Détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="<?php echo e(route('commandes.facture', $commande->id)); ?>" 
                                           class="btn btn-outline-primary" title="Facture" target="_blank">
                                            <i class="fas fa-file-invoice"></i>
                                        </a>
                                        <?php if($commande->statut === 'recue'): ?>
                                            <a href="<?php echo e(route('commandes.tickets-produits', $commande->id)); ?>"
                                               class="btn btn-outline-secondary" title="Tickets produits" target="_blank">
                                                <i class="fas fa-tags"></i>
                                            </a>
                                        <?php endif; ?>
                                        <a href="<?php echo e(route('commandes.edit', $commande->id)); ?>"
                                           class="btn btn-outline-warning" title="Modifier statut">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="<?php echo e(route('commandes.destroy', $commande->id)); ?>" 
                                              method="POST" class="d-inline">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-outline-danger" 
                                                    title="Supprimer"
                                                    onclick="return confirm('Supprimer cette commande ?')">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="py-4">
                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                        <p class="text-muted fs-5">Aucune commande trouvée</p>
                                        <a href="<?php echo e(route('commandes.create')); ?>" class="btn btn-primary mt-2">
                                            <i class="fas fa-plus me-2"></i>Créer une commande
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

            <?php if($commandes->hasPages()): ?>
                <div class="card-footer bg-white">
                    <div class="d-flex justify-content-center">
                        <?php echo e($commandes->links()); ?>

                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// Confirmation avant suppression
document.addEventListener('DOMContentLoaded', function() {
    const deleteForms = document.querySelectorAll('form[action*="destroy"]');
    deleteForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            if (!confirm('Êtes-vous sûr de vouloir supprimer cette commande ? Cette action est irréversible.')) {
                e.preventDefault();
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/commandes/index.blade.php ENDPATH**/ ?>