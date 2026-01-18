<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture d'Achat - {{ $commande->numero_commande }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.5;
            background: #fff;
            color: #333;
        }

        .facture-container {
            width: 210mm;
            margin: 10mm auto;
            padding: 15mm;
            background: white;
        }

        .header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 3px solid #2c3e50;
        }

        .company-info {
            flex: 1;
        }

        .company-name {
            font-size: 24px;
            font-weight: bold;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .company-details {
            font-size: 11px;
            color: #7f8c8d;
            line-height: 1.6;
        }

        .invoice-info {
            text-align: right;
        }

        .invoice-title {
            font-size: 28px;
            font-weight: bold;
            color: #e74c3c;
            margin-bottom: 5px;
        }

        .invoice-number {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
        }

        .info-section {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            gap: 20px;
        }

        .info-box {
            flex: 1;
            padding: 15px;
            background: #ecf0f1;
            border-radius: 5px;
        }

        .info-box h3 {
            font-size: 14px;
            color: #2c3e50;
            margin-bottom: 10px;
            padding-bottom: 5px;
            border-bottom: 2px solid #bdc3c7;
        }

        .info-row {
            display: flex;
            margin-bottom: 5px;
        }

        .info-label {
            font-weight: bold;
            width: 140px;
            color: #34495e;
        }

        .info-value {
            color: #2c3e50;
        }

        .products-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .products-table thead {
            background: #34495e;
            color: white;
        }

        .products-table th {
            padding: 12px 8px;
            text-align: left;
            font-weight: 600;
            font-size: 11px;
            text-transform: uppercase;
        }

        .products-table tbody tr {
            border-bottom: 1px solid #ecf0f1;
        }

        .products-table tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        .products-table td {
            padding: 10px 8px;
            font-size: 11px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }

        .totals-section {
            margin-top: 20px;
            display: flex;
            justify-content: flex-end;
        }

        .totals-box {
            width: 300px;
            background: #ecf0f1;
            padding: 15px;
            border-radius: 5px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }

        .total-row.grand-total {
            border-top: 2px solid #2c3e50;
            margin-top: 10px;
            padding-top: 12px;
            font-size: 18px;
            font-weight: bold;
            color: #e74c3c;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #ecf0f1;
            text-align: center;
            font-size: 10px;
            color: #7f8c8d;
        }

        .notes {
            margin-top: 20px;
            padding: 15px;
            background: #fff9e6;
            border-left: 4px solid #f39c12;
            border-radius: 3px;
        }

        .notes h4 {
            font-size: 13px;
            color: #f39c12;
            margin-bottom: 8px;
        }

        @media print {
            body { margin: 0; }
            .facture-container { margin: 0; box-shadow: none; }
            @page { margin: 10mm; }
        }
    </style>
</head>
<body>
<div class="facture-container">
    <!-- En-tête -->
    <div class="header">
        <div class="company-info">
            <div class="company-name">CLINIQUE DU SMARTPHONE</div>
            <div class="company-details">
                AV LA MARCHE VERTE<br>
                boumia , midelt<br>
                📞 +212 667765531<br>
                📧 achats@clinique-smartphone.fr<br>
                SIRET: 123 456 789 00012
            </div>
        </div>
        <div class="invoice-info">
            <div class="invoice-title">FACTURE D'ACHAT</div>
            <div class="invoice-number">{{ $commande->numero_commande }}</div>
            <div style="font-size: 11px; color: #7f8c8d;">
                Date: {{ $commande->date_commande->format('d/m/Y') }}<br>
                Statut: <strong>{{ ucfirst($commande->statut) }}</strong>
            </div>
        </div>
    </div>

    <!-- Informations -->
    <div class="info-section">
        <div class="info-box">
            <h3>📦 Fournisseur</h3>
            @if($commande->fournisseur)
            <div class="info-row">
                <span class="info-label">Nom:</span>
                <span class="info-value">{{ $commande->fournisseur->nom }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value">{{ $commande->fournisseur->email ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Téléphone:</span>
                <span class="info-value">{{ $commande->fournisseur->telephone ?? '-' }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Adresse:</span>
                <span class="info-value">{{ $commande->fournisseur->adresse ?? '-' }}</span>
            </div>
            @else
            <p style="color: #7f8c8d; font-style: italic;">Fournisseur non spécifié</p>
            @endif
        </div>

        <div class="info-box">
            <h3>📋 Détails de la commande</h3>
            <div class="info-row">
                <span class="info-label">Date de commande:</span>
                <span class="info-value">{{ $commande->date_commande->format('d/m/Y à H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Nombre de produits:</span>
                <span class="info-value">{{ $commande->produits->count() }}</span>
            </div>
            <div class="info-row">
                <span class="info-label">Quantité totale:</span>
                <span class="info-value">{{ $commande->produits->sum('quantite') }} unités</span>
            </div>
            <div class="info-row">
                <span class="info-label">Statut:</span>
                <span class="info-value">
                    @if($commande->statut === 'recue')
                        <span style="color: #27ae60;">✓ Reçue</span>
                    @elseif($commande->statut === 'en_attente')
                        <span style="color: #f39c12;">⏳ En attente</span>
                    @else
                        <span style="color: #e74c3c;">✗ Annulée</span>
                    @endif
                </span>
            </div>
        </div>
    </div>

    <!-- Tableau des produits -->
    <table class="products-table">
        <thead>
            <tr>
                <th style="width: 5%;">#</th>
                <th style="width: 10%;">Code</th>
                <th style="width: 30%;">Produit</th>
                <th style="width: 15%;">Catégorie</th>
                <th style="width: 10%;" class="text-center">Qté</th>
                <th style="width: 15%;" class="text-right">Prix Unit.</th>
                <th style="width: 15%;" class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
           // Dans la partie tableau des produits, modifiez :
@foreach($commande->produits as $index => $produit)
@php 
    // Utilisez les données du pivot (commande_produit) au lieu du produit directement
    $quantite = $produit->pivot->quantite;
    $prixAchat = $produit->pivot->prix_achat;
    $total_ligne = $quantite * $prixAchat;
    $subtotal += $total_ligne;
@endphp
<tr>
    <td>{{ $index + 1 }}</td>
    <td><small>{{ $produit->code }}</small></td>
    <td>
        <strong>{{ $produit->nom }}</strong>
        @if($produit->description)
        <br><small style="color: #7f8c8d;">{{ Str::limit($produit->description, 60) }}</small>
        @endif
    </td>
    <td>{{ $produit->categorie ? $produit->categorie->nom : '-' }}</td>
    <td class="text-center"><strong>{{ $quantite }}</strong></td>
    <td class="text-right">{{ number_format($prixAchat, 2) }} DH</td>
    <td class="text-right"><strong>{{ number_format($total_ligne, 2) }} DH</strong></td>
</tr>
@endforeach
        </tbody>
    </table>

    <!-- Totaux -->
    <div class="totals-section">
        <div class="totals-box">
            <div class="total-row grand-total">
                <span>TOTAL TTC:</span>
                <span>{{ number_format($subtotal) }} DH</span>
            </div>
        </div>
    </div>

    <!-- Notes -->
    @if($commande->notes)
    <div class="notes">
        <h4>📝 Notes</h4>
        <p>{{ $commande->notes }}</p>
    </div>
    @endif

    <!-- Pied de page -->
    <div class="footer">
        <p>Facture générée le {{ now()->format('d/m/Y à H:i') }}</p>
        <p style="margin-top: 5px;">Clinique du Smartphone - Tous droits réservés © {{ date('Y') }}</p>
    </div>
</div>

<script>
    window.onload = function() {
        window.print();
    };
</script>
</body>
</html>