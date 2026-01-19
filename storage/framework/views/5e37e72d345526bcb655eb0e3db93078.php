<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture d'Achat - <?php echo e($produit->nom); ?></title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 0;
            background: #fff;
        }

        .facture-container {
            width: 800px;
            margin: 20px auto;
            border: 1px solid #333;
            padding: 20px;
        }

        .facture-header, .facture-footer {
            text-align: center;
            margin-bottom: 20px;
        }

        .facture-header .company-name {
            font-size: 20px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .facture-header .company-info {
            font-size: 11px;
            color: #555;
        }

        .facture-title {
            text-align: center;
            font-weight: bold;
            font-size: 16px;
            margin-bottom: 10px;
            text-decoration: underline;
        }

        .facture-details, .product-section, .total-section, .barcode-section {
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 5px;
        }

        .detail-label {
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            padding: 6px 10px;
            border-bottom: 1px solid #333;
        }

        th {
            text-align: left;
            background: #f0f0f0;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 14px;
            margin-top: 10px;
            border-top: 1px solid #333;
            padding-top: 5px;
        }

        .barcode-section {
            text-align: center;
        }

        .barcode-section div {
            margin-top: 5px;
            font-size: 11px;
        }

        @media print {
            body { margin: 0; }
        }
    </style>
</head>
<body>
<div class="facture-container">
    <div class="facture-header">
        <div class="company-name">LA CLINIQUE DU SMARTPHONE</div>
        <div class="company-info">Achat de Matériel & Équipement</div>
        <div class="company-info">AV LA MARCHE VERTE BOUMIA</div>
        <div class="company-info">tel:0667765531/0660797507</div>
    </div>

    <div class="facture-title">FACTURE D'ACHAT</div>
    <div style="text-align:center; font-size:12px;">N° PROD-<?php echo e($produit->id); ?>-<?php echo e(date('Y')); ?></div>

    <div class="facture-details">
        <div class="detail-row"><span class="detail-label">Date d'achat:</span> <span><?php echo e($produit->date_achat ? $produit->date_achat->format('d/m/Y à H:i') : 'Non spécifiée'); ?></span></div>
        <div class="detail-row"><span class="detail-label">Code produit:</span> <span><?php echo e($produit->code); ?></span></div>
        <div class="detail-row"><span class="detail-label">Catégorie:</span> <span><?php echo e($produit->categorie ? $produit->categorie->nom : 'Non catégorisé'); ?></span></div>
        <?php if($produit->fournisseur): ?>
        <div class="detail-row"><span class="detail-label">Fournisseur:</span> <span><?php echo e($produit->fournisseur->nom); ?></span></div>
        <?php endif; ?>
        <div class="detail-row"><span class="detail-label">Quantité achetée:</span> <span><?php echo e($produit->quantite); ?> unité(s)</span></div>
    </div>

    <div class="product-section">
        <table>
            <thead>
                <tr>
                    <th>Description</th>
                    <th style="text-align:center;">Qté</th>
                    <th style="text-align:right;">Prix Unit.</th>
                    <th style="text-align:right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo e($produit->nom); ?> <?php if($produit->description): ?><br><small><?php echo e($produit->description); ?></small><?php endif; ?></td>
                    <td style="text-align:center;"><?php echo e($produit->quantite); ?></td>
                    <td style="text-align:right;"><?php echo e(number_format($produit->prix_achat, 2)); ?> DH</td>
                    <td style="text-align:right;"><?php echo e(number_format($produit->prix_achat * $produit->quantite, 2)); ?> DH</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="total-section">
        <div class="total-row">
            <span>TOTAL ACHAT:</span>
            <span><?php echo e(number_format($produit->prix_achat * $produit->quantite, 2)); ?> DH</span>
        </div>
    </div>

    <div class="barcode-section">
        <?php echo $barcode; ?>

       
    </div>

    <div class="facture-footer">
        <div>Facture générée le <?php echo e(now()->format('d/m/Y à H:i')); ?></div>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/facture-achat.blade.php ENDPATH**/ ?>