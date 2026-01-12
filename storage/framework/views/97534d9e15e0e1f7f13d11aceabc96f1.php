<!DOCTYPE html>
<html>
<head>
    <title>Ticket de Vente/Réparation</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            max-width: 300px;
            margin: 0 auto;
            padding: 10px;
        }
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        .logo {
            font-size: 18px;
            font-weight: bold;
        }
        .details {
            margin-bottom: 10px;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }
        .total {
            border-top: 1px solid #000;
            padding-top: 5px;
            font-weight: bold;
        }
        .footer {
            text-align: center;
            margin-top: 10px;
            font-size: 10px;
        }
        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <?php
        use Milon\Barcode\DNS1D;
        $dns = new DNS1D();
    ?>
    <div class="header">
        <div class="logo">Clinique du Smartphones</div>
        <div><?php echo e($type === 'vente' ? 'Ticket de Vente' : 'Ticket de Réparation'); ?></div>
        <div>Date: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
        <?php if($type === 'vente'): ?>
            <div>N° Vente: <?php echo e($data['id'] ?? 'N/A'); ?></div>
            <div>Code-barre:</div>
            <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($data['id'] ?? '', 'C128')); ?>" alt="Barcode" style="width: 200px; height: 50px;">
        <?php else: ?>
            <div>N° Réparation: <?php echo e($data['code'] ?? 'N/A'); ?></div>
            <div>Code-barre:</div>
            <img src="data:image/png;base64,<?php echo e($dns->getBarcodePNG($data['code'] ?? '', 'C128')); ?>" alt="Barcode" style="width: 200px; height: 50px;">
        <?php endif; ?>
    </div>

    <div class="details">
        <?php if($type === 'vente'): ?>
            <div><strong>Client:</strong> <?php echo e($data['client'] ?? 'Anonyme'); ?></div>
            <div><strong>Produits:</strong></div>
            <?php $__currentLoopData = $data['produits'] ?? []; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="item">
                    <span><?php echo e($produit['nom']); ?> (<?php echo e($produit['quantite']); ?>)</span>
                    <span><?php echo e(number_format($produit['prix_unitaire'] * $produit['quantite'], 2)); ?> DH</span>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <div class="total">
                <div class="item">
                    <span>Total:</span>
                    <span><?php echo e(number_format($data['total'] ?? 0, 2)); ?> DH</span>
                </div>
            </div>
        <?php else: ?>
            <div><strong>Client:</strong> <?php echo e($data['nom']); ?></div>
            <div><strong>Produit:</strong> <?php echo e($data['produit']); ?></div>
            <div><strong>Description:</strong> <?php echo e(Str::limit($data['description'], 50)); ?></div>
            <div class="total">
                <div class="item">
                    <span>Prix:</span>
                    <span><?php echo e(number_format($data['prix'], 2)); ?> DH</span>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="footer">
        Merci pour votre visite!<br>
        Clinique du Smartphones<br>
        Téléphone: 0667765531/0660797507<br>

    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html></content>
<file_path="c:\Users\marwan\clinique\resources\views\ticket.blade.php<?php /**PATH C:\Users\marwan\clinique\resources\views/ticket.blade.php ENDPATH**/ ?>