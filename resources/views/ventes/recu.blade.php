@extends('layouts.app')
@section('title', 'Reçu de Vente - ' . $vente->numero_vente)

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center mb-4">
                <button onclick="window.print()" class="print-btn">
                    <i class="fas fa-print"></i> Imprimer le reçu
                </button>
                <a href="{{ route('ventes.show', $vente) }}" class="btn btn-secondary ms-2">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>

            <div class="receipt-container">
                <!-- En-tête -->
                <div class="receipt-header">
                    <div class="store-name">CLINIQUE SMARTPHONE</div>
                    <div class="store-info">Spécialiste en réparation & vente</div>
                    <div class="store-info">📍 Rue de la Technologie, Ville</div>
                    <div class="store-info">📞 +33 1 23 45 67 89</div>
                </div>

                <!-- Titre du reçu -->
                <div class="receipt-title">REÇU DE VENTE</div>

                <!-- Détails de la vente -->
                <div class="receipt-details">
                    <div class="detail-row">
                        <span class="detail-label">N° Vente:</span>
                        <span class="detail-value">{{ $vente->numero_vente }}</span>
                    </div>
                    <div class="detail-row">
                        <span class="detail-label">Date:</span>
                        <span class="detail-value">{{ $vente->date_vente->format('d/m/Y H:i') }}</span>
                    </div>
                    @if($vente->utilisateur)
                    <div class="detail-row">
                        <span class="detail-label">Client:</span>
                        <span class="detail-value">{{ $vente->utilisateur->prenom }} {{ $vente->utilisateur->nom }}</span>
                    </div>
                    @else
                    <div class="detail-row">
                        <span class="detail-label">Client:</span>
                        <span class="detail-value">Anonyme</span>
                    </div>
                    @endif
                    <div class="detail-row">
                        <span class="detail-label">Statut:</span>
                        <span class="detail-value">
                            @if($vente->statut == 'finalisee')
                                Payée
                            @elseif($vente->statut == 'en_cours')
                                En cours
                            @else
                                Annulée
                            @endif
                        </span>
                    </div>
                </div>

                <!-- Section produit -->
                <div class="product-section">
                    <div class="product-name">{{ $vente->produit->nom }}</div>
                    <div class="product-details">
                        <span>Qté: {{ $vente->quantite }}</span>
                        <span>Prix unit.: {{ number_format($vente->prix_unitaire, 2) }} €</span>
                    </div>
                    @if($vente->produit->description)
                    <div style="font-size: 10px; color: #777; margin-top: 5px;">
                        {{ Str::limit($vente->produit->description, 50) }}
                    </div>
                    @endif
                </div>

                <!-- Section total -->
                <div class="total-section">
                    <div class="total-row">
                        <span>TOTAL:</span>
                        <span>{{ number_format($vente->prix_total, 2) }} €</span>
                    </div>
                </div>

                <!-- Code-barres -->
                @if($vente->produit->code)
                <div class="barcode-section">
                    {!! DNS1D::getBarcodeHTML($vente->produit->code, 'C128', 1, 33) !!}
                    <div style="font-size: 10px; margin-top: 5px;">{{ $vente->produit->code }}</div>
                </div>
                @endif

                <!-- Pied de page -->
                <div class="receipt-footer">
                    <div>Merci pour votre achat !</div>
                    <div>Conservez ce reçu pour tout échange</div>
                    <div style="margin-top: 10px; font-size: 9px;">
                        Reçu généré le {{ now()->format('d/m/Y à H:i') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
