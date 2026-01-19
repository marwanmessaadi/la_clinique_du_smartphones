<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ticket</title>
    <style>
        @page { size: 58mm auto; margin: 0; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            margin: 0;
            padding: 0;
        }
        .ticket {
            width: 58mm;
            padding: 6mm;
            text-align: center; /* centré horizontalement */
            box-sizing: border-box;
        }
        .header { border-bottom: 1px dashed #000; margin-bottom: 6px; padding-bottom: 4px; }
        .logo { font-size: 14px; font-weight: bold; }
        .product { font-weight: bold; margin-top: 4px; }
        .price { margin: 3px 0; }
        .barcode img { width: 100%; height: 45px; }
        .code { font-size: 9px; letter-spacing: 1px; }
        .footer { font-size: 9px; margin-top: 4px; }
        .mini-ticket { border-bottom: 1px dashed #000; margin-bottom: 6px; padding-bottom: 4px; }
    </style>
</head>
<body onload="window.print()">

<?php
use Milon\Barcode\DNS1D;
$dns = new DNS1D();
?>

<?php if(isset($type) && $type === 'reparation'): ?>
    <!-- Mini-ticket pour collage -->
    <div class="ticket mini-ticket">
        <div class="product">Code: <?php echo e($data['code']); ?></div>
        <div class="product">Problème: <?php echo e($data['description']); ?></div>
        <div class="product">Prix: <?php echo e(number_format($data['prix'],2)); ?> DH</div>
        <div class="barcode">
            <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($data['code'], 'C128')); ?>">
        </div>
        <div class="code"><?php echo e($data['code']); ?></div>
    </div>
<?php endif; ?>

<!-- Ticket principal -->
<div class="ticket">
    <div class="header">
        <div class="logo">Clinique du Smartphones</div>
        <div><?php echo e((isset($type) && $type === 'vente') ? 'Ticket de Vente' : 'Ticket de Réparation'); ?></div>
        <div>Date: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
    </div>

    <?php if(isset($type) && $type === 'vente'): ?>
        <div class="product">Client: <?php echo e($data['client']); ?></div>
        <?php $__currentLoopData = $data['produits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="product"><?php echo e($produit['nom']); ?> (<?php echo e($produit['quantite']); ?>)</div>
            <div class="price"><?php echo e(number_format($produit['prix_unitaire'] * $produit['quantite'],2)); ?> DH</div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <div class="price">Total: <?php echo e(number_format($data['total'],2)); ?> DH</div>
        <div class="barcode">
            <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($data['id'], 'C128')); ?>">
        </div>
        <div class="code"><?php echo e($data['id']); ?></div>
    <?php else: ?>
        <div class="product">Client: <?php echo e($data['nom']); ?></div>
        <div class="product">Produit: <?php echo e($data['produit']); ?></div>
        <div class="product">Prix: <?php echo e(number_format($data['prix'],2)); ?> DH</div>
        <div class="product">Description: <?php echo e($data['description']); ?></div>
        <div class="barcode">
            <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($data['code'], 'C128')); ?>">
        </div>
        <div class="code"><?php echo e($data['code']); ?></div>
    <?php endif; ?>

    <div class="footer">
        Merci pour votre visite!<br>
        Clinique du Smartphones<br>
        Téléphone: 0667765531 / 0660797507
    </div>
</div>
</body>
</html>
<?php /**PATH C:\Users\marwan\clinique\resources\views/ventes/recu.blade.php ENDPATH**/ ?>