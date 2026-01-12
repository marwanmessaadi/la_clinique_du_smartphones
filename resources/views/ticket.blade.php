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
    @php
        use Milon\Barcode\DNS1D;
        $dns = new DNS1D();
    @endphp
    <div class="header">
        <div class="logo">Clinique du Smartphones</div>
        <div>{{ $type === 'vente' ? 'Ticket de Vente' : 'Ticket de Réparation' }}</div>
        <div>Date: {{ now()->format('d/m/Y H:i') }}</div>
        @if($type === 'vente')
            <div>N° Vente: {{ $data['id'] ?? 'N/A' }}</div>
            <div>Code-barre:</div>
            <img src="data:image/png;base64,{{ $dns->getBarcodePNG($data['id'] ?? '', 'C128') }}" alt="Barcode" style="width: 200px; height: 50px;">
        @else
            <div>N° Réparation: {{ $data['code'] ?? 'N/A' }}</div>
            <div>Code-barre:</div>
            <img src="data:image/png;base64,{{ $dns->getBarcodePNG($data['code'] ?? '', 'C128') }}" alt="Barcode" style="width: 200px; height: 50px;">
        @endif
    </div>

    <div class="details">
        @if($type === 'vente')
            <div><strong>Client:</strong> {{ $data['client'] ?? 'Anonyme' }}</div>
            <div><strong>Produits:</strong></div>
            @foreach($data['produits'] ?? [] as $produit)
                <div class="item">
                    <span>{{ $produit['nom'] }} ({{ $produit['quantite'] }})</span>
                    <span>{{ number_format($produit['prix_unitaire'] * $produit['quantite'], 2) }} DH</span>
                </div>
            @endforeach
            <div class="total">
                <div class="item">
                    <span>Total:</span>
                    <span>{{ number_format($data['total'] ?? 0, 2) }} DH</span>
                </div>
            </div>
        @else
            <div><strong>Client:</strong> {{ $data['nom'] }}</div>
            <div><strong>Produit:</strong> {{ $data['produit'] }}</div>
            <div><strong>Description:</strong> {{ Str::limit($data['description'], 50) }}</div>
            <div class="total">
                <div class="item">
                    <span>Prix:</span>
                    <span>{{ number_format($data['prix'], 2) }} DH</span>
                </div>
            </div>
        @endif
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
<file_path="c:\Users\marwan\clinique\resources\views\ticket.blade.php