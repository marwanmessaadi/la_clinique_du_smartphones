<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ticket Réparation - <?php echo e(isset($reparation) ? $reparation->code : ($data['code'] ?? 'N/A')); ?></title>
    <style>
        /* Styles pour impression */
        @media print {
            @page {
                size: 80mm auto;
                margin: 0;
            }
            
            body {
                margin: 0;
                padding: 5mm;
                font-size: 10px;
                width: 80mm;
            }
            
            .no-print {
                display: none !important;
            }
        }
        
        /* Styles écran */
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            max-width: 80mm;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #ccc;
            background: white;
        }
        
        .header {
            text-align: center;
            border-bottom: 2px dashed #000;
            padding-bottom: 10px;
            margin-bottom: 10px;
        }
        
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
            text-transform: uppercase;
        }
        
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 5px 0;
        }
        
        .info-section {
            margin: 10px 0;
        }
        
        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 4px 0;
        }
        
        .info-label {
            font-weight: bold;
            min-width: 100px;
        }
        
        .info-value {
            text-align: right;
            flex: 1;
        }
        
        .barcode-container {
            text-align: center;
            margin: 15px 0;
            padding: 10px;
            border: 1px solid #ccc;
            background: #f9f9f9;
        }
        
        .text-barcode {
            font-family: 'Courier New', monospace;
            font-size: 20px;
            letter-spacing: 4px;
            font-weight: bold;
            margin: 10px 0;
            padding: 10px;
            border: 2px solid #000;
            background: white;
            display: inline-block;
        }
        
        .total {
            border-top: 2px solid #000;
            padding-top: 10px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 14px;
            text-align: right;
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px dashed #ccc;
            font-size: 10px;
            color: #666;
        }
        
        .actions {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
        }
        
        .btn {
            padding: 8px 16px;
            margin: 0 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
        }
        
        .btn-print {
            background: #007bff;
            color: white;
        }
        
        .btn-print:hover {
            background: #0056b3;
        }
        
        .btn-close {
            background: #6c757d;
            color: white;
        }
        
        .btn-close:hover {
            background: #545b62;
        }
        
        .description-box {
            border: 1px solid #ccc;
            padding: 8px;
            min-height: 40px;
            background: #f9f9f9;
            border-radius: 3px;
        }
        
        .notes-box {
            border: 1px dotted #ccc;
            padding: 5px;
            font-size: 11px;
            background: #fffbf0;
            border-radius: 3px;
        }
    </style>
</head>
<body>
    <?php
        // Utiliser $reparation si disponible, sinon $data
        $ticketData = isset($reparation) ? [
            'code' => $reparation->code ?? 'N/A',
            'nom' => $reparation->nom ?? 'N/A',
            'produit' => $reparation->produit ?? 'N/A',
            'etat' => $reparation->etat ?? 'N/A',
            'date_reparation' => $reparation->date_reparation,
            'prix' => $reparation->prix ?? 0,
            'description' => $reparation->description ?? '',
            'notes' => $reparation->notes ?? '',
            'barcode' => $barcode ?? null,
        ] : ($data ?? []);
    ?>

    <!-- En-tête -->
    <div class="header">
        <h1 class="title">TICKET RÉPARATION</h1>
        <p class="subtitle"><?php echo e(config('app.name', 'Votre Magasin')); ?></p>
        <p><?php echo e(now()->format('d/m/Y H:i')); ?></p>
    </div>
    
    <!-- Informations principales -->
    <div class="info-section">
        <div class="info-row">
            <span class="info-label">Code:</span>
            <span class="info-value"><strong><?php echo e($ticketData['code']); ?></strong></span>
        </div>
        <div class="info-row">
            <span class="info-label">Client:</span>
            <span class="info-value"><?php echo e($ticketData['nom']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">Produit:</span>
            <span class="info-value"><?php echo e($ticketData['produit']); ?></span>
        </div>
        <div class="info-row">
            <span class="info-label">État:</span>
            <span class="info-value">
                <?php
                    $etatLabels = [
                        'en_cours' => 'En cours',
                        'terminee' => 'Terminée',
                        'annulee' => 'Annulée'
                    ];
                    $etatLabel = $etatLabels[$ticketData['etat']] ?? ucfirst($ticketData['etat']);
                ?>
                <?php echo e($etatLabel); ?>

            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Date:</span>
            <span class="info-value">
                <?php if(isset($ticketData['date_reparation']) && $ticketData['date_reparation']): ?>
                    <?php echo e(\Carbon\Carbon::parse($ticketData['date_reparation'])->format('d/m/Y H:i')); ?>

                <?php else: ?>
                    <?php echo e(now()->format('d/m/Y H:i')); ?>

                <?php endif; ?>
            </span>
        </div>
        <div class="info-row">
            <span class="info-label">Prix:</span>
            <span class="info-value"><strong><?php echo e(number_format($ticketData['prix'], 2, ',', ' ')); ?> DH</strong></span>
        </div>
    </div>
    
    <!-- Description -->
    <?php if(!empty($ticketData['description'])): ?>
    <div class="info-section">
        <div style="font-weight: bold; margin-bottom: 5px;">Description:</div>
        <div class="description-box">
            <?php echo e($ticketData['description']); ?>

        </div>
    </div>
    <?php endif; ?>
    
    <!-- Code-barres -->
    <div class="barcode-container">
        <?php if(isset($ticketData['barcode']) && !empty($ticketData['barcode'])): ?>
            <?php echo $ticketData['barcode']; ?>

        <?php else: ?>
            <div class="text-barcode">
                <?php echo e($ticketData['code']); ?>

            </div>
            <div style="font-size: 12px; color: #666; margin-top: 5px;">
                Code réparation
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Notes -->
    <?php if(!empty($ticketData['notes'])): ?>
    <div class="info-section">
        <div style="font-weight: bold; margin-bottom: 5px;">Notes:</div>
        <div class="notes-box">
            <?php echo e($ticketData['notes']); ?>

        </div>
    </div>
    <?php endif; ?>
    
    <!-- Total -->
    <div class="total">
        TOTAL À PAYER: <?php echo e(number_format($ticketData['prix'], 2, ',', ' ')); ?> DH
    </div>
    
    <!-- Pied de page -->
    <div class="footer">
        <p><strong>Merci pour votre confiance !</strong></p>
        <p>Ticket généré le <?php echo e(now()->format('d/m/Y à H:i')); ?></p>
        <p style="margin-top: 5px;">Pour toute réclamation, présentez ce ticket</p>
    </div>
    
    <!-- Boutons d'action (non imprimés) -->
    <div class="actions no-print">
        <button onclick="window.print()" class="btn btn-print">
            🖨️ Imprimer
        </button>
        <button onclick="window.close()" class="btn btn-close">
            ✖️ Fermer
        </button>
    </div>
    
    <script>
        // Auto-impression au chargement (optionnel - décommenter si souhaité)
        // window.addEventListener('load', function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // });
        
        // Fermeture automatique après impression (optionnel)
        window.addEventListener('afterprint', function() {
            // window.close(); // Décommenter pour fermer automatiquement
        });
    </script>
</body>
</html><?php /**PATH C:\Users\marwan\clinique\resources\views/reparations/barcode.blade.php ENDPATH**/ ?>