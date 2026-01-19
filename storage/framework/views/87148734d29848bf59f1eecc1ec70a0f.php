<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <style>
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none !important; }
        .facture-container { box-shadow: none; margin: 0; }
        .print-btn { display: none; }
    }

    .facture-container {
        max-width: 800px;
        margin: 2rem auto;
        background: white;
        border: 2px solid #333;
        border-radius: 8px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        font-family: 'Courier New', monospace;
        font-size: 12px;
        line-height: 1.4;
    }

    .facture-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 20px;
        text-align: center;
        border-radius: 6px 6px 0 0;
    }

    .company-name {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .company-info {
        font-size: 11px;
        opacity: 0.9;
        margin: 2px 0;
    }

    .facture-title {
        font-size: 18px;
        font-weight: bold;
        margin: 15px 0 10px 0;
        text-decoration: underline;
        color: #333;
    }

    .facture-details {
        padding: 20px;
        border-bottom: 1px solid #ddd;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        padding: 5px 0;
    }

    .detail-label {
        font-weight: bold;
        color: #555;
    }

    .detail-value {
        color: #333;
    }

    .product-section {
        padding: 20px;
        border-bottom: 1px solid #ddd;
        background: #f9f9f9;
    }

    .product-name {
        font-size: 16px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #333;
        text-align: center;
    }

    .product-table {
        width: 100%;
        border-collapse: collapse;
        margin: 15px 0;
    }

    .product-table th,
    .product-table td {
        padding: 8px 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    .product-table th {
        background: #f0f0f0;
        font-weight: bold;
        color: #333;
    }

    .total-section {
        padding: 20px;
        background: #f0f0f0;
        border-bottom: 1px solid #ddd;
    }

    .total-row {
        display: flex;
        justify-content: space-between;
        font-size: 16px;
        font-weight: bold;
        color: #333;
        padding: 10px 0;
        border-top: 2px solid #333;
        margin-top: 10px;
    }

    .barcode-section {
        padding: 20px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    .facture-footer {
        padding: 20px;
        text-align: center;
        font-size: 11px;
        color: #666;
        background: #f9f9f9;
        border-radius: 0 0 6px 6px;
    }

    .footer-text {
        margin: 5px 0;
    }

    .print-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 25px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin: 10px;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.3);
    }

    .print-btn:hover {
        background: linear-gradient(45deg, #218838, #1aa085);
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.4);
    }

    .no-print {
        text-align: center;
        margin-bottom: 20px;
    }

    .status-badge {
        display: inline-block;
        padding: 4px 12px;
        border-radius: 15px;
        font-size: 10px;
        font-weight: bold;
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
</style>

<?php $__env->startSection('content'); ?>
<div class="no-print">
    <button onclick="window.print()" class="print-btn">
        <i class="fas fa-print"></i> Imprimer la Facture
    </button>
    <a href="<?php echo e(route('ventes.show', $vente)); ?>" class="print-btn" style="background: linear-gradient(45deg, #6c757d, #495057);">
        <i class="fas fa-arrow-left"></i> Retour aux Détails
    </a>
</div>

<div class="facture-container">
    <!-- En-tête de l'entreprise -->
    <div class="facture-header">
        <div class="company-name">CLINIQUE DU SMARTPHONE</div>
        
    </div>

    <!-- Titre de la facture -->
    <div style="text-align: center; padding: 15px; background: #fff; border-bottom: 1px solid #ddd;">
        <div class="facture-title">FACTURE DE VENTE</div>
        <div style="font-size: 14px; color: #666;">N° <?php echo e($vente->numero_vente); ?></div>
    </div>

    <!-- Informations de la facture -->
    <div class="facture-details">
        <div class="detail-row">
            <span class="detail-label">Date de vente:</span>
            <span class="detail-value"><?php echo e($vente->date_vente->format('d/m/Y à H:i')); ?></span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Client:</span>
            <span class="detail-value">
                <?php if($vente->utilisateur): ?>
                    <?php echo e($vente->utilisateur->prenom); ?> <?php echo e($vente->utilisateur->nom); ?>

                <?php else: ?>
                    Client anonyme
                <?php endif; ?>
            </span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Statut:</span>
            <span class="detail-value">
                <span class="status-badge status-<?php echo e($vente->statut); ?>">
                    <?php if($vente->statut === 'finalisee'): ?>
                        Payée
                    <?php elseif($vente->statut === 'en_cours'): ?>
                        En cours
                    <?php else: ?>
                        Annulée
                    <?php endif; ?>
                </span>
            </span>
        </div>

        <?php if($vente->notes): ?>
        <div class="detail-row">
            <span class="detail-label">Notes:</span>
            <span class="detail-value"><?php echo e($vente->notes); ?></span>
        </div>
        <?php endif; ?>
    </div>

    <!-- Section produit -->
    <div class="product-section">
        <div class="product-name">DÉTAIL DE L'ARTICLE</div>

        <table class="product-table">
            <thead>
                <tr>
                    <th style="width: 50%;">Description</th>
                    <th style="width: 15%; text-align: center;">Qté</th>
                    <th style="width: 20%; text-align: right;">Prix Unit.</th>
                    <th style="width: 15%; text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <strong><?php echo e($vente->produit->nom); ?></strong>
                        <?php if($vente->produit->description): ?>
                        <br><small style="color: #666;"><?php echo e(Str::limit($vente->produit->description, 80)); ?></small>
                        <?php endif; ?>
                        <?php if($vente->produit->categorie): ?>
                        <br><small style="color: #666;">Catégorie: <?php echo e($vente->produit->categorie->nom); ?></small>
                        <?php endif; ?>
                    </td>
                    <td style="text-align: center;"><?php echo e($vente->quantite); ?></td>
                    <td style="text-align: right;"><?php echo e(number_format($vente->prix_unitaire, 2)); ?> DH</td>
                    <td style="text-align: right;"><?php echo e(number_format($vente->prix_total, 2)); ?> DH</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section total -->
    <div class="total-section">
        <div class="total-row">
            <span>TOTAL À PAYER:</span>
            <span><?php echo e(number_format($vente->prix_total, 2)); ?> DH</span>
        </div>
    </div>

    <!-- Code-barres -->
    <?php if($vente->produit->code): ?>
    <div class="barcode-section">
        <div style="margin-bottom: 10px; font-weight: bold; color: #333;">Code Article:</div>
        <?php echo DNS1D::getBarcodeHTML($vente->produit->code, 'C128', 1, 40); ?>

        <div style="margin-top: 8px; font-size: 11px; color: #666;"><?php echo e($vente->produit->code); ?></div>
    </div>
    <?php endif; ?>

    <!-- Pied de page -->
    <div class="facture-footer">
        <div class="footer-text"><strong>CONDITIONS DE VENTE</strong></div>
        <div class="footer-text">• Garantie 6 mois sur les réparations</div>
        <div class="footer-text">• Échange sous 7 jours avec facture</div>
        <div class="footer-text">• Paiement accepté: Espèces, Carte bancaire, Chèque</div>
        <div class="footer-text" style="margin-top: 15px;">
            <strong>Merci pour votre confiance !</strong>
        </div>
        <div class="footer-text" style="margin-top: 10px; font-size: 10px; color: #999;">
            Facture générée le <?php echo e(now()->format('d/m/Y à H:i')); ?> - CLINIQUE DU SMARTPHONE
        </div>
    </div>
</div>

<script>
    // Auto-focus pour l'impression
    window.onload = function() {
        // Optionnel: auto-impression
        // setTimeout(function() { window.print(); }, 500);
    };
</script>
</body>
</html>
<?php /**PATH C:\Users\marwan\clinique\resources\views/ventes/facture.blade.php ENDPATH**/ ?>