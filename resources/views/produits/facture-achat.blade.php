@extends('layouts.app')
@section('title', 'Facture d\'Achat - ' . $produit->nom)

@push('styles')
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
        background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
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
        text-align: center;
        margin-bottom: 10px;
        color: #333;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 5px;
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

    .supplier-info {
        background: rgba(255,255,255,0.8);
        padding: 15px;
        border-radius: 8px;
        margin-bottom: 15px;
        border: 1px solid #dee2e6;
    }

    .supplier-title {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #28a745;
    }
</style>
@endpush

@section('content')
<div class="no-print">
    <button onclick="window.print()" class="print-btn">
        <i class="fas fa-print"></i> Imprimer la Facture d'Achat
    </button>
    <a href="{{ route('produits.show', $produit) }}" class="print-btn" style="background: linear-gradient(45deg, #6c757d, #495057);">
        <i class="fas fa-arrow-left"></i> Retour au Produit
    </a>
</div>

<div class="facture-container">
    <!-- En-tête de l'entreprise -->
    <div class="facture-header">
        <div class="company-name">CLINIQUE DU SMARTPHONE</div>
        <div class="company-info">Achat de Matériel & Équipement</div>
        <div class="company-info">📍 123 Rue de la Technologie, 75001 Paris</div>
        <div class="company-info">📞 +33 1 23 45 67 89 | 📧 achats@clinique-smartphone.fr</div>
        <div class="company-info">🕒 Service Achats - Réception Marchandises</div>
    </div>

    <!-- Titre de la facture -->
    <div style="text-align: center; padding: 15px; background: #fff; border-bottom: 1px solid #ddd;">
        <div class="facture-title">FACTURE D'ACHAT</div>
        <div style="font-size: 14px; color: #666;">N° PROD-{{ $produit->id }}-{{ date('Y') }}</div>
    </div>

    <!-- Informations de la facture -->
    <div class="facture-details">
        <div class="detail-row">
            <span class="detail-label">Date d'achat:</span>
            <span class="detail-value">{{ $produit->date_achat ? $produit->date_achat->format('d/m/Y à H:i') : 'Non spécifiée' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Code produit:</span>
            <span class="detail-value">{{ $produit->code ?? 'Non généré' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Catégorie:</span>
            <span class="detail-value">{{ $produit->categorie ? $produit->categorie->nom : 'Non catégorisée' }}</span>
        </div>

        <div class="detail-row">
            <span class="detail-label">Quantité achetée:</span>
            <span class="detail-value">{{ $produit->quantite }} unité(s)</span>
        </div>

        @if($produit->notes)
        <div class="detail-row">
            <span class="detail-label">Notes:</span>
            <span class="detail-value">{{ $produit->notes }}</span>
        </div>
        @endif
    </div>

    <!-- Informations fournisseur -->
    @if($produit->fournisseur)
    <div class="supplier-info">
        <div class="supplier-title">📦 INFORMATIONS FOURNISSEUR</div>
        <div class="detail-row">
            <span class="detail-label">Nom:</span>
            <span class="detail-value">{{ $produit->fournisseur->nom }}</span>
        </div>
        @if($produit->fournisseur->contact)
        <div class="detail-row">
            <span class="detail-label">Contact:</span>
            <span class="detail-value">{{ $produit->fournisseur->contact }}</span>
        </div>
        @endif
        @if($produit->fournisseur->telephone)
        <div class="detail-row">
            <span class="detail-label">Téléphone:</span>
            <span class="detail-value">{{ $produit->fournisseur->telephone }}</span>
        </div>
        @endif
        @if($produit->fournisseur->email)
        <div class="detail-row">
            <span class="detail-label">Email:</span>
            <span class="detail-value">{{ $produit->fournisseur->email }}</span>
        </div>
        @endif
    </div>
    @endif

    <!-- Section produit -->
    <div class="product-section">
        <div class="product-name">DÉTAIL DE L'ARTICLE ACHETÉ</div>

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
                        <strong>{{ $produit->nom }}</strong>
                        @if($produit->description)
                        <br><small style="color: #666;">{{ Str::limit($produit->description, 80) }}</small>
                        @endif
                        @if($produit->code)
                        <br><small style="color: #666;">Code interne: {{ $produit->code }}</small>
                        @endif
                    </td>
                    <td style="text-align: center;">{{ $produit->quantite }}</td>
                    <td style="text-align: right;">{{ number_format($produit->prix_achat, 2) }} €</td>
                    <td style="text-align: right;">{{ number_format($produit->prix_achat * $produit->quantite, 2) }} €</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Section total -->
    <div class="total-section">
        <div class="total-row">
            <span>TOTAL ACHAT:</span>
            <span>{{ number_format($produit->prix_achat * $produit->quantite, 2) }} €</span>
        </div>
    </div>

    <!-- Code-barres -->
    <div class="barcode-section">
        <div style="margin-bottom: 10px; font-weight: bold; color: #333;">Code Article (à coller sur le produit):</div>
        {!! $barcode !!}
        <div style="margin-top: 8px; font-size: 11px; color: #666;">{{ $produit->code }}</div>
    </div>

    <!-- Pied de page -->
    <div class="facture-footer">
        <div class="footer-text"><strong>CONDITIONS D'ACHAT</strong></div>
        <div class="footer-text">• Produit vérifié et accepté à la réception</div>
        <div class="footer-text">• Garantie fournisseur applicable selon conditions</div>
        <div class="footer-text">• Stock mis à jour automatiquement dans le système</div>
        <div class="footer-text">• Code-barres à apposer sur chaque unité</div>
        <div class="footer-text" style="margin-top: 15px;">
            <strong>Service Achats - Clinique du Smartphone</strong>
        </div>
        <div class="footer-text" style="margin-top: 10px; font-size: 10px; color: #999;">
            Facture générée le {{ now()->format('d/m/Y à H:i') }} - Système de Gestion Interne
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
@endsection