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

@php
use Milon\Barcode\DNS1D;
$dns = new DNS1D();
@endphp

@if(isset($type) && $type === 'reparation')
    <!-- Mini-ticket pour collage -->
    <div class="ticket mini-ticket">
        <div class="product">Code: {{ $data['code'] }}</div>
        <div class="product">Problème: {{ $data['description'] }}</div>
        <div class="product">Prix: {{ number_format($data['prix'],2) }} DH</div>
        <div class="barcode">
            <img src="data:image/png;base64,{{ $dns->getBarcodePNG($data['code'], 'C128') }}">
        </div>
        <div class="code">{{ $data['code'] }}</div>
    </div>
@endif

<!-- Ticket principal -->
<div class="ticket">
    <div class="header">
        <div class="logo">Clinique du Smartphones</div>
        <div>{{ (isset($type) && $type === 'vente') ? 'Ticket de Vente' : 'Ticket de Réparation' }}</div>
        <div>Date: {{ now()->format('d/m/Y H:i') }}</div>
    </div>

    @if(isset($type) && $type === 'vente')
        <div class="product">Client: {{ $data['client'] }}</div>
        @foreach($data['produits'] as $produit)
            <div class="product">{{ $produit['nom'] }} ({{ $produit['quantite'] }})</div>
            <div class="price">{{ number_format($produit['prix_unitaire'] * $produit['quantite'],2) }} DH</div>
        @endforeach
        <div class="price">Total: {{ number_format($data['total'],2) }} DH</div>
        <div class="barcode">
            <img src="data:image/png;base64,{{ $dns->getBarcodePNG($data['id'], 'C128') }}">
        </div>
        <div class="code">{{ $data['id'] }}</div>
    @else
        <div class="product">Client: {{ $data['nom'] }}</div>
        <div class="product">Produit: {{ $data['produit'] }}</div>
        <div class="product">Prix: {{ number_format($data['prix'],2) }} DH</div>
        <div class="product">Description: {{ $data['description'] }}</div>
        <div class="barcode">
            <img src="data:image/png;base64,{{ $dns->getBarcodePNG($data['code'], 'C128') }}">
        </div>
        <div class="code">{{ $data['code'] }}</div>
    @endif

    <div class="footer">
        Merci pour votre visite!<br>
        Clinique du Smartphones<br>
        Téléphone: 0667765531 / 0660797507
    </div>
</div>
</body>
</html>
