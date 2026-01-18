<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Tickets Produits - {{ $commande->numero_commande }}</title>

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

        /* Grille verrouillée à 5 colonnes */
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
            page-break-inside: avoid;
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

@php
    use Milon\Barcode\DNS1D;
    $dns = new DNS1D();
@endphp

<div class="tickets-wrapper">

{{-- CORRECTION: Utilisez $commande->produits au lieu de $commande->produitsCommande --}}
@if($commande->produits && $commande->produits->count() > 0)
    @foreach($commande->produits as $produit)
        @for($i = 0; $i < $produit->pivot->quantite; $i++)
            <div class="ticket">
                <div class="logo">Clinique du Smartphones</div>

                <div class="product">{{ $produit->nom }}</div>
                @if($produit->description)
                <div class="description" style="font-size: 7px; margin: 1px 0;">
                    {{ \Illuminate\Support\Str::limit($produit->description, 30) }}
                </div>
                @endif

                <div class="price">{{ number_format($produit->pivot->prix_vente, 2) }} DH</div>

                <div class="barcode">
                    <img src="data:image/png;base64,{{ $dns->getBarcodePNG($produit->code ?? $produit->id, 'C128') }}">
                </div>

                <div class="code">{{ $produit->code ?? $produit->id }}</div>
                
                <div style="font-size: 6px; margin-top: 2px;">
                    CMD: {{ $commande->numero_commande }}
                </div>
            </div>
        @endfor
    @endforeach
@else
    <div style="grid-column: 1 / -1; text-align: center; padding: 20px;">
        <p>Aucun produit dans cette commande.</p>
    </div>
@endif

</div>

</body>
</html>