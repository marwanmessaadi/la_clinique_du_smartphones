<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tickets Produits</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        .tickets-wrapper {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 2mm;
        }

        .ticket {
            width: 100%;
            padding: 4px;
            text-align: center;
            border: 1px dashed #000;
            box-sizing: border-box;
        }

        .logo {
            font-size: 10px;
            font-weight: bold;
        }

        .product {
            font-size: 8px;
            font-weight: bold;
            margin-top: 2px;
        }

        .price {
            font-size: 8px;
            margin: 2px 0;
        }

        .barcode img {
            width: 100%;
            height: 32px;
        }

        .code {
            font-size: 7px;
            letter-spacing: 1px;
        }
    </style>
</head>

<body onload="window.print()">

<?php
    use Milon\Barcode\DNS1D;
    $dns = new DNS1D();

    // Si un produit unique est passé, on le met dans un tableau pour boucler
    if(isset($produit)) {
        $produits = [$produit];
    }
?>

<div class="tickets-wrapper">

<?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produitItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
        $code = $produitItem->code ?? '000000';
        $quantite = $produitItem->quantite ?? 1;
    ?>

    <?php for($i = 0; $i < $quantite; $i++): ?>
        <div class="ticket">
            <div class="logo">Clinique du Smartphones</div>
            <div class="product"><?php echo e($produitItem->nom); ?><?php echo e($produitItem->description ? ' _ '.$produitItem->description : ''); ?></div>
            <div class="price"><?php echo e(number_format($produitItem->prix_vente, 2)); ?> DH</div>
            <div class="barcode">
                <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($code, 'C128')); ?>">
            </div>
            <div class="code"><?php echo e($code); ?></div>
        </div>
    <?php endfor; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

</div>

</body>
</html>
<?php /**PATH C:\Users\marwan\clinique\resources\views/produits/ticket.blade.php ENDPATH**/ ?>