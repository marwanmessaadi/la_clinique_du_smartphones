@extends('layouts.app')
@section('title', 'Ticket Produit - ' . $produit->nom)

@push('styles')
<style>
    @media print {
        body { margin: 0; padding: 0; }
        .no-print { display: none !important; }
        .ticket-container { box-shadow: none; margin: 0; page-break-inside: avoid; }
        .print-btn { display: none; }
    }

    .ticket-container {
        width: 300px;
        margin: 20px auto;
        background: white;
        border: 2px solid #333;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        font-family: 'Courier New', monospace;
        font-size: 11px;
        line-height: 1.3;
        position: relative;
        overflow: hidden;
    }

    .ticket-container::before {
        content: '';
        position: absolute;
        top: 50%;
        left: -10px;
        width: 20px;
        height: 20px;
        background: white;
        border: 2px solid #333;
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .ticket-container::after {
        content: '';
        position: absolute;
        top: 50%;
        right: -10px;
        width: 20px;
        height: 20px;
        background: white;
        border: 2px solid #333;
        border-radius: 50%;
        transform: translateY(-50%);
    }

    .ticket-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 8px 15px;
        text-align: center;
        position: relative;
    }

    .ticket-header::before {
        content: '';
        position: absolute;
        bottom: -10px;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 0;
        border-left: 10px solid transparent;
        border-right: 10px solid transparent;
        border-top: 10px solid #667eea;
    }

    .company-name {
        font-size: 12px;
        font-weight: bold;
        margin-bottom: 2px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .ticket-subtitle {
        font-size: 9px;
        opacity: 0.9;
        margin: 0;
    }

    .ticket-body {
        padding: 12px 15px;
    }

    .product-name {
        font-size: 14px;
        font-weight: bold;
        text-align: center;
        margin-bottom: 8px;
        color: #333;
        border-bottom: 1px dashed #ccc;
        padding-bottom: 5px;
    }

    .product-details {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 10px;
    }

    .detail-item {
        margin-bottom: 4px;
    }

    .detail-label {
        font-weight: bold;
        color: #555;
    }

    .detail-value {
        color: #333;
    }

    .ticket-barcode {
        text-align: center;
        margin: 10px 0;
        padding: 8px;
        background: #f9f9f9;
        border-radius: 4px;
    }

    .ticket-footer {
        background: #f0f0f0;
        padding: 8px 15px;
        text-align: center;
        border-top: 1px dashed #ccc;
        font-size: 9px;
        color: #666;
    }

    .print-btn {
        background: linear-gradient(45deg, #28a745, #20c997);
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 20px;
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

    .ticket-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, 300px);
        gap: 20px;
        justify-content: center;
        margin: 20px 0;
    }

    @media print {
        .ticket-grid {
            display: block;
        }

        .ticket-container {
            margin: 10px auto;
            page-break-inside: avoid;
        }

        .ticket-container:not(:last-child) {
            margin-bottom: 20px;
        }
    }
</style>
@endpush

@section('content')
<div class="no-print">
    <button onclick="window.print()" class="print-btn">
        <i class="fas fa-print"></i> Imprimer le(s) Ticket(s)
    </button>
    <a href="{{ route('produits.show', $produit) }}" class="print-btn" style="background: linear-gradient(45deg, #6c757d, #495057);">
        <i class="fas fa-arrow-left"></i> Retour au Produit
    </a>
</div>

<div class="ticket-grid">
    <!-- Générer plusieurs tickets si demandé via paramètre GET -->
    @php
        $quantity = request('quantity', 1);
        $quantity = min(max(1, $quantity), 10); // Limiter entre 1 et 10 tickets
    @endphp

    @for($i = 0; $i < $quantity; $i++)
    <div class="ticket-container">
        <div class="ticket-header">
            <div class="company-name">CLINIQUE SMARTPHONE</div>
            <p class="ticket-subtitle">Ticket Produit</p>
        </div>

        <div class="ticket-body">
            <div class="product-name">{{ $produit->nom }}</div>

            <div class="product-details">
                <div class="detail-item">
                    <span class="detail-label">Code:</span>
                    <span class="detail-value">{{ $produit->code }}</span>
                </div>
                <div class="detail-item">
                    <span class="detail-label">Prix:</span>
                    <span class="detail-value">{{ number_format($produit->prix_vente, 2) }} €</span>
                </div>
            </div>

            @if($produit->categorie)
            <div class="detail-item">
                <span class="detail-label">Catégorie:</span>
                <span class="detail-value">{{ $produit->categorie->nom }}</span>
            </div>
            @endif

            @if($produit->description)
            <div style="font-size: 9px; color: #666; margin-top: 5px; text-align: center; border-top: 1px dashed #eee; padding-top: 5px;">
                {{ Str::limit($produit->description, 60) }}
            </div>
            @endif

            <div class="ticket-barcode">
                {!! $barcode !!}
            </div>
        </div>

        <div class="ticket-footer">
            <div>À coller sur le produit</div>
            <div>{{ now()->format('d/m/Y H:i') }}</div>
        </div>
    </div>
    @endfor
</div>

<div class="no-print" style="text-align: center; margin-top: 20px;">
    <form method="GET" style="display: inline-block;">
        <label for="quantity" style="margin-right: 10px; font-weight: bold;">Nombre de tickets:</label>
        <select name="quantity" id="quantity" onchange="this.form.submit()" style="padding: 5px; border-radius: 4px; border: 1px solid #ccc;">
            @for($i = 1; $i <= 10; $i++)
                <option value="{{ $i }}" {{ $i == $quantity ? 'selected' : '' }}>{{ $i }}</option>
            @endfor
        </select>
    </form>
</div>

<script>
    // Auto-focus pour l'impression
    window.onload = function() {
        // Optionnel: auto-impression après un délai
        // setTimeout(function() { window.print(); }, 500);
    };
</script>
@endsection
