
<?php $__env->startSection('title', 'Détails Réparation'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- En-tête -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="mb-0">
                                <i class="fas fa-tools me-2"></i>Réparation #<?php echo e(e($reparation->code ?? 'N/A')); ?>

                            </h5>
                            <small class="opacity-75">Créée le <?php echo e($reparation->created_at->format('d/m/Y à H:i')); ?></small>
                        </div>
                        <span class="badge bg-<?php echo e($reparation->etat == 'terminee' ? 'success' : ($reparation->etat == 'en_cours' ? 'warning' : 'danger')); ?> fs-6">
                            <?php echo e(ucfirst(str_replace('_', ' ', $reparation->etat))); ?>

                        </span>
                    </div>
                </div>
            </div>

            <!-- Informations principales -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-info-circle me-2"></i>Informations Client</h6>
                        </div>
                        <div class="card-body">
                            <table class="table table-borderless">
                                <tr>
                                    <th width="40%">Nom du client:</th>
                                    <td><?php echo e(e($reparation->nom)); ?></td>
                                </tr>
                                <tr>
                                    <th>Produit:</th>
                                    <td><?php echo e(e($reparation->produit)); ?></td>
                                </tr>
                                <tr>
                                    <th>Date réparation:</th>
                                    <td>
                                        <?php if($reparation->date_reparation): ?>
                                            <?php echo e($reparation->date_reparation->format('d/m/Y à H:i')); ?>

                                        <?php else: ?>
                                            <span class="text-muted">Non définie</span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="card h-100">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-euro-sign me-2"></i>Informations Financières</h6>
                        </div>
                        <div class="card-body">
                            <div class="text-center py-3">
                                <h3 class="text-primary"><?php echo e(number_format($reparation->prix, 2)); ?> DH</h3>
                                <p class="text-muted mb-0">Prix total de la réparation</p>
                            </div>
                            <div class="mt-3">
                                <strong>Code:</strong> <?php echo e(e($reparation->code ?? 'Non généré')); ?>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Description et Notes -->
            <div class="row mb-4">
                <?php if($reparation->description): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-file-alt me-2"></i>Description</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?php echo e(nl2br(e($reparation->description))); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php if($reparation->notes): ?>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0"><i class="fas fa-sticky-note me-2"></i>Notes</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-0"><?php echo e(nl2br(e($reparation->notes))); ?></p>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>

            <!-- Actions -->
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between flex-wrap">
                        <div class="btn-group mb-2">
                            <a href="<?php echo e(route('reparation.barcode', $reparation)); ?>" 
                               class="btn btn-outline-primary" target="_blank">
                                <i class="fas fa-barcode me-1"></i> Code-barres
                            </a>
                            
                            <?php if($reparation->code): ?>
                            <a href="<?php echo e(route('reparation.ticket', $reparation)); ?>" 
                               class="btn btn-outline-secondary" target="_blank">
                                <i class="fas fa-print me-1"></i> Imprimer ticket
                            </a>
                            <?php endif; ?>
                        </div>
                        
                        <div class="btn-group mb-2">
                            <?php if(auth()->user()->can('update', $reparation)): ?>
                            <a href="<?php echo e(route('reparation.edit', $reparation)); ?>" 
                               class="btn btn-warning">
                                <i class="fas fa-edit me-1"></i> Modifier
                            </a>
                            <?php endif; ?>
                            
                            <?php if(auth()->user()->can('delete', $reparation)): ?>
                            <form action="<?php echo e(route('reparation.destroy', $reparation)); ?>" 
                                  method="POST" 
                                  class="d-inline"
                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette réparation ? Cette action est irréversible.');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger">
                                    <i class="fas fa-trash me-1"></i> Supprimer
                                </button>
                            </form>
                            <?php endif; ?>
                            
                            <a href="<?php echo e(route('reparation.index')); ?>" class="btn btn-secondary">
                                <i class="fas fa-list me-1"></i> Liste
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/show.blade.php ENDPATH**/ ?>