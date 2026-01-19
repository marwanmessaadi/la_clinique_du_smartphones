
<?php $__env->startSection('title', 'Détails de la Vente - ' . $vente->numero_vente); ?>

<?php $__env->startSection('content'); ?>
<style>
.detail-container {
    max-width: 1000px;
    margin: 2rem auto;
    background: #ffffff;
    border-radius: 12px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
    overflow: hidden;
}

.detail-header {
    background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
    color: white;
    padding: 2rem;
    text-align: center;
}

.detail-title {
    font-size: 2rem;
    font-weight: 600;
    margin: 0 0 0.5rem 0;
}

.detail-subtitle {
    font-size: 1.1rem;
    opacity: 0.9;
    margin: 0;
}

.detail-body {
    padding: 2rem;
}

.detail-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.detail-item {
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 1.5rem;
    border-left: 4px solid #6f42c1;
}

.detail-label {
    font-size: 0.9rem;
    color: #718096;
    text-transform: uppercase;
    font-weight: 600;
    margin-bottom: 0.5rem;
}

.detail-value {
    font-size: 1.1rem;
    color: #2d3748;
    font-weight: 500;
}

.price-highlight {
    color: #e83e8c !important;
    font-size: 1.3rem !important;
    font-weight: 700 !important;
}

.status-badge {
    display: inline-block;
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.9rem;
    font-weight: 600;
    text-transform: uppercase;
}

.status-finalisee {
    background: #d4edda;
    color: #155724;
}

.status-en_cours {
    background: #fff3cd;
    color: #856404;
}

.status-annulee {
    background: #f8d7da;
    color: #721c24;
}

.product-section {
    background: #f8fafc;
    border-radius: 0.5rem;
    padding: 1.5rem;
    margin-bottom: 2rem;
}

.product-header {
    margin-bottom: 1.5rem;
}

.product-name {
    font-size: 1.5rem;
    color: #2d3748;
    margin: 0 0 0.5rem 0;
}

.action-buttons {
    display: flex;
    gap: 1rem;
    justify-content: center;
    flex-wrap: wrap;
    margin-top: 2rem;
}

.btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1.5rem;
    font-size: 0.9rem;
    font-weight: 500;
    border-radius: 0.5rem;
    text-decoration: none;
    transition: all 0.2s ease;
    border: none;
    cursor: pointer;
}

.btn-print {
    background: #28a745;
    color: white;
}

.btn-print:hover {
    background: #218838;
    transform: translateY(-1px);
}

.btn-warning {
    background: #ffc107;
    color: #212529;
}

.btn-warning:hover {
    background: #e0a800;
    transform: translateY(-1px);
}

.btn-secondary {
    background: #6c757d;
    color: white;
}

.btn-secondary:hover {
    background: #5a6268;
    transform: translateY(-1px);
}
</style>
<div class="detail-container">
    <div class="detail-header">
        <h1 class="detail-title">Détails de la Vente</h1>
        <p class="detail-subtitle"><?php echo e($vente->numero_vente); ?></p>
    </div>

    <div class="detail-body">
        <!-- Status -->
        <div class="text-center mb-4">
            <span class="status-badge status-<?php echo e($vente->statut); ?>">
                <?php if($vente->statut === 'finalisee'): ?>
                    Vente Finalisée
                <?php elseif($vente->statut === 'en_cours'): ?>
                    Vente en Cours
                <?php else: ?>
                    Vente Annulée
                <?php endif; ?>
            </span>
        </div>

        <!-- Informations générales -->
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Date de vente</div>
                <div class="detail-value"><?php echo e($vente->date_vente->format('d/m/Y H:i')); ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Client</div>
                <div class="detail-value">
                    <?php if($vente->utilisateur): ?>
                        <?php echo e($vente->utilisateur->prenom); ?> <?php echo e($vente->utilisateur->nom); ?>

                    <?php else: ?>
                        Client anonyme
                    <?php endif; ?>
                </div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Quantité</div>
                <div class="detail-value"><?php echo e($vente->quantite); ?></div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Prix unitaire</div>
                <div class="detail-value"><?php echo e(number_format($vente->prix_unitaire, 2)); ?> DH</div>
            </div>

            <div class="detail-item">
                <div class="detail-label">Prix total</div>
                <div class="detail-value price-highlight"><?php echo e(number_format($vente->prix_total, 2)); ?> DH</div>
            </div>
        </div>

        <!-- Informations produit -->
        <div class="product-section">
            <div class="product-header">
                <h3 class="product-name"><?php echo e($vente->produit->nom); ?></h3>
                <?php if($vente->produit->code): ?>
                    <small class="text-muted">Code: <?php echo e($vente->produit->code); ?></small>
                <?php endif; ?>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?php if($vente->produit->image): ?>
                        <img src="<?php echo e(asset('storage/' . $vente->produit->image)); ?>"
                             alt="<?php echo e($vente->produit->nom); ?>"
                             class="img-fluid rounded"
                             style="max-height: 200px;">
                    <?php endif; ?>
                </div>
                <div class="col-md-6">
                    <?php if($vente->produit->description): ?>
                        <p><strong>Description:</strong> <?php echo e($vente->produit->description); ?></p>
                    <?php endif; ?>
                    <p><strong>Catégorie:</strong> <?php echo e($vente->produit->categorie ? $vente->produit->categorie->nom : 'Non catégorisé'); ?></p>
                    <?php if($vente->produit->fournisseur): ?>
                        <p><strong>Fournisseur:</strong> <?php echo e($vente->produit->fournisseur->nom); ?></p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Notes -->
        <?php if($vente->notes): ?>
            <div class="detail-item">
                <div class="detail-label">Notes</div>
                <div class="detail-value"><?php echo e($vente->notes); ?></div>
            </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="action-buttons">
            <a href="<?php echo e(route('ventes.facture', $vente)); ?>" class="btn btn-print" target="_blank">
                <i class="fas fa-file-invoice"></i> Imprimer la Facture
            </a>
            <a href="<?php echo e(route('ventes.recu', $vente)); ?>" class="btn btn-print" target="_blank" style="background: linear-gradient(45deg, #17a2b8, #138496);">
                <i class="fas fa-receipt"></i> Imprimer le Reçu
            </a>
            <a href="<?php echo e(route('ventes.edit', $vente)); ?>" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="<?php echo e(route('ventes.index')); ?>" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/ventes/show.blade.php ENDPATH**/ ?>