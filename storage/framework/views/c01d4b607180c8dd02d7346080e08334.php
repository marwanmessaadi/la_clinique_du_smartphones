
<?php $__env->startSection('title', 'Code Barre - ' . $produit->code); ?>

<?php $__env->startSection('content'); ?>
<style>
    .barcode-container {
        max-width: 600px;
        margin: 2rem auto;
        background: #ffffff;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .barcode-header {
        background: #f8f9fa;
        padding: 1.5rem 2rem;
        border-bottom: 1px solid #e9ecef;
        text-align: center;
    }

    .barcode-title {
        font-size: 1.5rem;
        color: #2c3e50;
        font-weight: 600;
        margin: 0;
    }

    .barcode-body {
        padding: 2rem;
        text-align: center;
    }

    .barcode-info {
        margin-bottom: 2rem;
    }

    .barcode-label {
        display: block;
        font-weight: 600;
        color: #495057;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
    }

    .barcode-value {
        color: #6c757d;
        font-size: 1.1rem;
        font-family: monospace;
        background: #f8f9fa;
        padding: 0.5rem;
        border-radius: 4px;
        border: 1px solid #e9ecef;
    }

    .barcode-display {
        margin: 2rem 0;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 1px solid #e9ecef;
    }

    .print-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
        transition: background-color 0.2s;
    }

    .print-btn:hover {
        background: #218838;
        color: white;
        text-decoration: none;
    }

    .back-btn {
        background: #6c757d;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 6px;
        font-weight: 500;
        text-decoration: none;
        display: inline-block;
        margin-top: 1rem;
        margin-left: 1rem;
        transition: background-color 0.2s;
    }

    .back-btn:hover {
        background: #5a6268;
        color: white;
        text-decoration: none;
    }
</style>

<div class="barcode-container">
    <div class="barcode-header">
        <h1 class="barcode-title">Code Barre du Produit</h1>
    </div>

    <div class="barcode-body">
        <div class="barcode-info">
            <div class="barcode-label">Nom du Produit</div>
            <div class="barcode-value"><?php echo e($produit->nom); ?></div>
        </div>

        <div class="barcode-info">
            <div class="barcode-label">Code Produit</div>
            <div class="barcode-value"><?php echo e($produit->code); ?></div>
        </div>

        <div class="barcode-info">
            <div class="barcode-label">Prix de Vente</div>
            <div class="barcode-value"><?php echo e(number_format($produit->prix_vente, 2)); ?> DH</div>
        </div>

        <div class="barcode-display">
            <?php echo $barcode; ?>

        </div>

        <div>
            <a href="<?php echo e(route('produits.ticket', $produit)); ?>" class="print-btn" target="_blank">
                🖨️ Imprimer Ticket
            </a>
            <a href="<?php echo e(route('produits.show', $produit)); ?>" class="back-btn">
                ← Retour au Produit
            </a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/barcode.blade.php ENDPATH**/ ?>