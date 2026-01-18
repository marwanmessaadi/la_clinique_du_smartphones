<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket - <?php echo e($type === 'vente' ? 'Vente' : 'Réparation'); ?></title>
    <style>
        @page { 
            size: 58mm auto; 
            margin: 0; 
        }
        
        @media print {
            body { margin: 0; padding: 0; }
        }
        
        body { 
            font-family: 'Courier New', monospace; 
            font-size: 11px; 
            margin: 0;
            padding: 0;
        }
        
        .ticket { 
            width: 58mm; 
            padding: 6mm; 
            text-align: center; 
        }
        
        .header { 
            border-bottom: 1px dashed #000; 
            margin-bottom: 6px; 
            padding-bottom: 4px; 
        }
        
        .logo { 
            font-size: 14px; 
            font-weight: bold; 
            margin-bottom: 3px;
        }
        
        .product { 
            font-weight: bold; 
            margin-top: 4px; 
        }
        
        .price { 
            margin: 3px 0; 
        }
        
        .barcode-container { 
            margin: 10px 0;
            padding: 5px;
            background: #f9f9f9;
            border: 1px solid #ddd;
        }
        
        .barcode-text { 
            font-family: 'Courier New', monospace;
            font-size: 16px; 
            letter-spacing: 3px;
            font-weight: bold;
            padding: 8px;
            background: white;
            border: 2px solid #000;
            display: inline-block;
            margin: 5px 0;
        }
        
        .code { 
            font-size: 9px; 
            letter-spacing: 1px;
            color: #666;
            margin-top: 3px;
        }
        
        .footer { 
            font-size: 9px; 
            margin-top: 8px;
            padding-top: 6px;
            border-top: 1px dashed #000;
        }
        
        .mini-ticket { 
            border-bottom: 2px dashed #000; 
            margin-bottom: 8px; 
            padding-bottom: 6px; 
        }
        
        .info-line {
            margin: 2px 0;
            font-size: 10px;
        }
        
        .total {
            font-size: 12px;
            font-weight: bold;
            margin-top: 6px;
            padding-top: 4px;
            border-top: 1px solid #000;
        }
    </style>
</head>
<body onload="window.print()">

<?php
    // Prépare les données selon le type
    if ($type === 'reparation') {
        $ticketData = isset($reparation) ? [
            'code' => $reparation->code ?? 'N/A',
            'nom' => $reparation->nom ?? 'N/A',
            'produit' => $reparation->produit ?? 'N/A',
            'prix' => $reparation->prix ?? 0,
            'description' => $reparation->description ?? '',
        ] : ($data ?? []);
    } else {
        $ticketData = $data ?? [];
    }
?>

<?php if($type === 'reparation'): ?>
    <!-- Mini-ticket pour collage sur le produit -->
    <div class="ticket mini-ticket">
        <div class="product">Code: <?php echo e($ticketData['code'] ?? 'N/A'); ?></div>
        <div class="product">Problème: <?php echo e(Str::limit($ticketData['description'] ?? '', 30)); ?></div>
        <div class="product">Prix: <?php echo e(number_format($ticketData['prix'] ?? 0, 2)); ?> DH</div>
        
        <div class="barcode-container">
            <?php if(isset($barcode) && !empty($barcode)): ?>
                <?php echo $barcode; ?>

            <?php else: ?>
                <div class="barcode-text"><?php echo e($ticketData['code'] ?? 'N/A'); ?></div>
                <div class="code">Code réparation</div>
            <?php endif; ?>
        </div>
    </div>
<?php endif; ?>

<!-- Ticket principal -->
<div class="ticket">
    <div class="header">
        <div class="logo">Clinique du Smartphones</div>
        <div><?php echo e($type === 'vente' ? 'TICKET DE VENTE' : 'TICKET DE RÉPARATION'); ?></div>
        <div>Date: <?php echo e(now()->format('d/m/Y H:i')); ?></div>
    </div>

    <?php if($type === 'vente'): ?>
        <!-- Ticket de vente -->
        <div class="info-line"><strong>Client:</strong> <?php echo e($ticketData['client'] ?? 'Anonyme'); ?></div>
        <div class="info-line"><strong>N° Vente:</strong> <?php echo e($ticketData['id'] ?? 'N/A'); ?></div>
        
        <div style="margin-top: 8px; border-top: 1px dashed #000; padding-top: 4px;">
            <?php if(isset($ticketData['produits']) && is_array($ticketData['produits'])): ?>
                <?php $__currentLoopData = $ticketData['produits']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="product"><?php echo e($produit['nom'] ?? 'Produit'); ?></div>
                    <div class="info-line">
                        Qté: <?php echo e($produit['quantite'] ?? 1); ?> × <?php echo e(number_format($produit['prix_unitaire'] ?? 0, 2)); ?> DH
                    </div>
                    <div class="price">
                        = <?php echo e(number_format(($produit['prix_unitaire'] ?? 0) * ($produit['quantite'] ?? 1), 2)); ?> DH
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
        
        <div class="total">
            TOTAL: <?php echo e(number_format($ticketData['total'] ?? 0, 2)); ?> DH
        </div>
        
        <div class="barcode-container">
            <?php if(isset($barcode) && !empty($barcode)): ?>
                <?php echo $barcode; ?>

            <?php else: ?>
                <div class="barcode-text"><?php echo e($ticketData['id'] ?? 'N/A'); ?></div>
                <div class="code">Numéro de vente</div>
            <?php endif; ?>
        </div>
        
    <?php else: ?>
        <!-- Ticket de réparation -->
        <div class="info-line"><strong>Code:</strong> <?php echo e($ticketData['code'] ?? 'N/A'); ?></div>
        <div class="info-line"><strong>Client:</strong> <?php echo e($ticketData['nom'] ?? 'N/A'); ?></div>
        <div class="info-line"><strong>Produit:</strong> <?php echo e($ticketData['produit'] ?? 'N/A'); ?></div>
        
        <?php if(!empty($ticketData['description'])): ?>
        <div style="margin: 6px 0; padding: 4px; background: #f9f9f9; border: 1px solid #ddd;">
            <div style="font-size: 9px; font-weight: bold;">Description:</div>
            <div style="font-size: 9px; text-align: left;"><?php echo e($ticketData['description']); ?></div>
        </div>
        <?php endif; ?>
        
        <div class="total">
            Prix: <?php echo e(number_format($ticketData['prix'] ?? 0, 2)); ?> DH
        </div>
        
        <div class="barcode-container">
            <?php if(isset($barcode) && !empty($barcode)): ?>
                <?php echo $barcode; ?>

            <?php else: ?>
                <div class="barcode-text"><?php echo e($ticketData['code'] ?? 'N/A'); ?></div>
                <div class="code">Code réparation</div>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div class="footer">
        <strong>Merci pour votre visite!</strong><br>
        Clinique du Smartphones<br>
        Tél: 0667765531 / 0660797507<br>
        <small>Conservez ce ticket</small>
    </div>
</div>

</body>
</html><?php /**PATH C:\Users\marwan\clinique\resources\views/ticket.blade.php ENDPATH**/ ?>