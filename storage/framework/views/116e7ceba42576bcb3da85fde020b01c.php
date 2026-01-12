
<?php $__env->startSection('title', 'Code Barre - ' . $reparation->code); ?>

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
        padding: 0.5rem 1rem;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .barcode-display {
        margin: 2rem 0;
        padding: 2rem;
        background: #f8f9fa;
        border-radius: 8px;
        border: 2px solid #e9ecef;
    }

    .barcode-image {
        margin: 1rem 0;
    }

    .barcode-footer {
        padding: 1.5rem 2rem;
        background: #f8f9fa;
        border-top: 1px solid #e9ecef;
        display: flex;
        justify-content: center;
        gap: 1rem;
    }

    .btn {
        display: inline-block;
        font-weight: 500;
        text-align: center;
        vertical-align: middle;
        padding: 0.75rem 1.5rem;
        font-size: 1rem;
        line-height: 1.5;
        border-radius: 6px;
        transition: all 0.15s ease-in-out;
        cursor: pointer;
        text-decoration: none;
    }

    .btn-primary {
        color: #fff;
        background-color: #0d6efd;
        border: 1px solid #0a58ca;
    }

    .btn-primary:hover {
        background-color: #0a58ca;
        border-color: #0a53be;
    }

    .btn-secondary {
        color: #fff;
        background-color: #6c757d;
        border: 1px solid #5c636a;
    }

    .btn-secondary:hover {
        background-color: #5c636a;
        border-color: #565e64;
    }

    .print-section {
        margin-top: 1rem;
        padding: 1rem;
        background: #e7f3ff;
        border-radius: 6px;
        border: 1px solid #b3d9ff;
    }

    .print-title {
        font-size: 0.9rem;
        font-weight: 600;
        color: #0056b3;
        margin-bottom: 0.5rem;
    }

    @media print {
        .barcode-header,
        .barcode-footer,
        .print-section {
            display: none;
        }

        .barcode-container {
            box-shadow: none;
            border: 1px solid #ddd;
        }

        .barcode-body {
            padding: 1rem;
        }
    }
</style>

<div class="barcode-container">
    <div class="barcode-header">
        <h2 class="barcode-title">Code Barre de la Réparation</h2>
    </div>

    <div class="barcode-body">
        <div class="barcode-info">
            <span class="barcode-label">Client</span>
            <div class="barcode-value"><?php echo e($reparation->nom); ?></div>

            <span class="barcode-label">Code</span>
            <div class="barcode-value"><?php echo e($reparation->code); ?></div>
        </div>

        <div class="barcode-display">
            <div class="barcode-image">
                <?php echo $barcode; ?>

            </div>
        </div>

        <div class="print-section">
            <div class="print-title">💡 Conseil d'impression</div>
            <p style="margin: 0; font-size: 0.85rem; color: #0056b3;">
                Pour une meilleure lisibilité, imprimez cette page en mode paysage et utilisez du papier de qualité.
            </p>
        </div>
    </div>

    <div class="barcode-footer">
        <a href="<?php echo e(route('reparation.show', $reparation)); ?>" class="btn btn-secondary">
            Retour aux détails
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            Imprimer
        </button>
    </div>
</div>

<script>
    // Auto-print functionality (optional)
    // window.onload = function() {
    //     window.print();
    // };
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/barcode.blade.php ENDPATH**/ ?>