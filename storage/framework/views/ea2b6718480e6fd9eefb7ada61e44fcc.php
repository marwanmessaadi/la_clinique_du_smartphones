
<?php $__env->startSection('title', 'Détails de la Réparation'); ?>

<?php $__env->startPush('styles'); ?>
<style>
.detail-container {
    max-width: 1200px;
    margin: 2rem auto;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.1);
    overflow: hidden;
    color: white;
}

.detail-header {
    background: rgba(255,255,255,0.1);
    padding: 2rem;
    text-align: center;
    backdrop-filter: blur(10px);
}

.detail-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 0.5rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.detail-subtitle {
    font-size: 1.2rem;
    opacity: 0.9;
    margin-bottom: 0;
}

.detail-body {
    padding: 2rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-item {
    background: rgba(255,255,255,0.1);
    padding: 1.5rem;
    border-radius: 15px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.detail-item:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

.detail-label {
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1.2rem;
    font-weight: 500;
}

.price-highlight {
    font-size: 1.5rem;
    font-weight: 700;
    color: #ffd700;
    text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.barcode-section {
    background: rgba(255,255,255,0.1);
    padding: 2rem;
    border-radius: 15px;
    margin-bottom: 2rem;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255,255,255,0.2);
    text-align: center;
}

.barcode-title {
    font-size: 1.3rem;
    font-weight: 600;
    margin-bottom: 1rem;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.status-badge {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    font-size: 0.9rem;
}

.status-en_cours {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: white;
}

.status-terminee {
    background: linear-gradient(45deg, #28a745, #20c997);
    color: white;
}

.status-annulee {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    color: white;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    padding: 0.75rem 1.5rem;
    border-radius: 25px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    border: none;
    min-width: 150px;
}

.btn-warning {
    background: linear-gradient(45deg, #ffc107, #fd7e14);
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(45deg, #e0a800, #d8590b);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255,193,7,0.4);
}

.btn-danger {
    background: linear-gradient(45deg, #dc3545, #e83e8c);
    color: white;
}

.btn-danger:hover {
    background: linear-gradient(45deg, #c82333, #bd2130);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(220,53,69,0.4);
}

.btn-secondary {
    background: linear-gradient(45deg, #6c757d, #495057);
    color: white;
}

.btn-secondary:hover {
    background: linear-gradient(45deg, #545b62, #343a40);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(108,117,125,0.4);
}

.btn-primary {
    background: linear-gradient(45deg, #007bff, #6610f2);
    color: white;
}

.btn-primary:hover {
    background: linear-gradient(45deg, #0056b3, #5a0fc8);
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,123,255,0.4);
}

@media (max-width: 768px) {
    .detail-container {
        margin: 1rem;
        border-radius: 15px;
    }

    .detail-header {
        padding: 1.5rem;
    }

    .detail-title {
        font-size: 2rem;
    }

    .detail-body {
        padding: 1.5rem;
    }

    .detail-grid {
        grid-template-columns: 1fr;
        gap: 1rem;
    }

    .action-buttons {
        flex-direction: column;
        align-items: center;
    }

    .action-buttons .btn {
        width: 100%;
        max-width: 300px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>

<div class="detail-container">
    <div class="detail-header">
        <h1 class="detail-title">Détails de la Réparation</h1>
        <p class="detail-subtitle"><?php echo e($reparation->nom); ?></p>
    </div>

    <div class="detail-body">
        <!-- Informations générales -->
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Nom du Client</div>
                <div class="detail-value"><?php echo e($reparation->nom); ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Produit</div>
                <div class="detail-value"><?php echo e($reparation->produit); ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Prix</div>
                <div class="price-highlight"><?php echo e(number_format($reparation->prix, 2)); ?> €</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Date de Réparation</div>
                <div class="detail-value"><?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'Non définie'); ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">État</div>
                <div class="detail-value">
                    <span class="status-badge status-<?php echo e($reparation->etat); ?>">
                        <?php echo e(ucfirst(str_replace('_', ' ', $reparation->etat))); ?>

                    </span>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Code Barre</div>
                <div class="detail-value"><?php echo e($reparation->code ?? 'Non généré'); ?></div>
            </div>
        </div>

        <?php if($reparation->description): ?>
            <div class="detail-item">
                <div class="detail-label">Description</div>
                <div class="detail-value"><?php echo e($reparation->description); ?></div>
            </div>
        <?php endif; ?>

        <div class="barcode-section">
            <div class="barcode-title">Code Barre</div>
            <?php if($reparation->code): ?>
                <a href="<?php echo e(route('reparation.barcode', $reparation)); ?>" class="btn btn-primary">
                    <i class="fas fa-barcode"></i> Voir le Code Barre
                </a>
            <?php else: ?>
                <p style="margin-bottom: 1rem; color: rgba(255,255,255,0.8);">Aucun code-barres n'a encore été généré pour cette réparation.</p>
                <a href="<?php echo e(route('reparation.barcode', $reparation)); ?>" class="btn btn-primary">
                    <i class="fas fa-barcode"></i> Générer et Voir le Code Barre
                </a>
            <?php endif; ?>
        </div>

        <!-- Actions -->
        <div class="action-buttons">
            <a href="<?php echo e(route('reparation.edit', $reparation)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="<?php echo e(route('reparation.destroy', $reparation)); ?>" method="POST" style="display: inline;">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="btn btn-danger"
                        onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette réparation ?')">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
            <a href="<?php echo e(route('reparation.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/show.blade.php ENDPATH**/ ?>