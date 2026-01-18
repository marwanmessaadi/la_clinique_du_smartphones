@extends('layouts.app')
@section('title', 'Détails du Produit')

@push('styles')
<style>
/* === Ton CSS existant exactement === */
.detail-container { max-width: 1200px; margin: 2rem auto; background: linear-gradient(135deg,#667eea 0%,#764ba2 100%); border-radius: 20px; box-shadow:0 20px 40px rgba(0,0,0,0.1); overflow:hidden; color:white; }
.detail-header { background: rgba(255,255,255,0.1); padding:2rem; text-align:center; backdrop-filter:blur(10px);}
.detail-title { font-size:2.5rem; font-weight:700; margin-bottom:0.5rem; text-shadow:0 2px 4px rgba(0,0,0,0.3);}
.detail-subtitle { font-size:1.2rem; opacity:0.9; margin-bottom:0;}
.detail-body { padding:2rem;}
.detail-grid { display:grid; grid-template-columns: repeat(auto-fit,minmax(300px,1fr)); gap:1.5rem; margin-bottom:2rem;}
.detail-item { background: rgba(255,255,255,0.1); padding:1.5rem; border-radius:15px; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.2); transition: transform 0.3s ease, box-shadow 0.3s ease;}
.detail-item:hover { transform: translateY(-5px); box-shadow:0 10px 25px rgba(0,0,0,0.2);}
.detail-label { font-size:0.9rem; font-weight:600; text-transform:uppercase; letter-spacing:1px; opacity:0.8; margin-bottom:0.5rem;}
.detail-value { font-size:1.2rem; font-weight:500;}
.price-value { font-size:1.5rem; font-weight:700; color:#ffd700; text-shadow:0 2px 4px rgba(0,0,0,0.3);}
.product-image { border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.3); margin-bottom:1rem;}
.barcode-section { background: rgba(255,255,255,0.1); padding:2rem; border-radius:15px; margin-bottom:2rem; backdrop-filter:blur(10px); border:1px solid rgba(255,255,255,0.2); text-align:center;}
.barcode-title { font-size:1.3rem; font-weight:600; margin-bottom:1rem; text-transform:uppercase; letter-spacing:1px;}
.action-buttons { display:flex; gap:1rem; justify-content:center; flex-wrap:wrap;}
.action-buttons .btn { padding:0.75rem 1.5rem; border-radius:25px; font-weight:600; text-transform:uppercase; letter-spacing:1px; transition:all 0.3s ease; border:none; min-width:150px;}
.btn-print { background: linear-gradient(45deg,#007bff,#6610f2); color:white;}
.btn-print:hover { background: linear-gradient(45deg,#0056b3,#5a0fc8); transform:translateY(-2px); box-shadow:0 5px 15px rgba(0,123,255,0.4);}
.btn-warning { background: linear-gradient(45deg,#ffc107,#fd7e14); color:white;}
.btn-warning:hover { background: linear-gradient(45deg,#e0a800,#d8590b); transform:translateY(-2px); box-shadow:0 5px 15px rgba(255,193,7,0.4);}
.btn-secondary { background: linear-gradient(45deg,#6c757d,#495057); color:white;}
.btn-secondary:hover { background: linear-gradient(45deg,#545b62,#343a40); transform:translateY(-2px); box-shadow:0 5px 15px rgba(108,117,125,0.4);}
</style>
@endpush

@section('content')
<div class="detail-container">
    <div class="detail-header">
        <h1 class="detail-title">Détails du Produit</h1>
        <p class="detail-subtitle">{{ $produit->nom }}</p>
    </div>

    <div class="detail-body">
        <div class="detail-grid">
            <div class="detail-item">
                <div class="detail-label">Prix d'achat</div>
                <div class="price-value">{{ number_format($produit->prix_achat,2) }} DH</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Prix de vente</div>
                <div class="price-value">{{ number_format($produit->prix_vente,2) }} DH</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Prix de gros</div>
                <div class="price-value">{{ $produit->prix_gros ? number_format($produit->prix_gros,2).' DH' : '-' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Quantité en stock</div>
                <div class="detail-value">
                    @if($produit->quantite>0)
                        <span class="badge bg-success">{{ $produit->quantite }}</span>
                    @else
                        <span class="badge bg-danger">Rupture de stock</span>
                    @endif
                </div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Catégorie</div>
                <div class="detail-value">{{ $produit->categorie ? $produit->categorie->nom : 'Non renseignée' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Fournisseur</div>
                <div class="detail-value">{{ $produit->fournisseur ? $produit->fournisseur->nom : 'Non renseigné' }}</div>
            </div>
            <div class="detail-item">
                <div class="detail-label">Date d'achat</div>
                <div class="detail-value">{{ $produit->date_achat ? $produit->date_achat->format('d/m/Y H:i') : 'Non renseignée' }}</div>
            </div>
        </div>

        @if($produit->description)
            <div class="detail-item" style="margin-bottom:2rem;">
                <div class="detail-label">Description</div>
                <div class="detail-value">{{ $produit->description }}</div>
            </div>
        @endif

        @if($produit->image)
            <div class="text-center">
                <img src="{{ Str::startsWith($produit->image,'http') ? $produit->image : asset('storage/'.$produit->image) }}"
                     alt="Image du produit"
                     class="product-image"
                     style="max-width:300px;">
            </div>
        @endif

        <div class="barcode-section">
            <div class="barcode-title">Code Barre & Tickets</div>
            <div class="d-flex justify-content-center gap-2 flex-wrap">
                <a href="{{ route('produits.barcode', $produit->id) }}" class="btn btn-info">
                    <i class="fas fa-barcode"></i> Voir Code Barre
                </a>
                <a href="{{ route('produits.ticket', $produit->id) }}" class="btn btn-success" target="_blank">
                    <i class="fas fa-tag"></i> Imprimer Ticket
                </a>
                <a href="{{ route('produits.ticket', $produit->id) }}?quantity=5" class="btn btn-warning" target="_blank">
                    <i class="fas fa-tags"></i> Tickets x5
                </a>
                @if($produit->type==='achat')
                <a href="{{ route('produits.facture-achat', $produit->id) }}" class="btn btn-primary" target="_blank">
                    <i class="fas fa-file-invoice-dollar"></i> Facture Achat
                </a>
                @endif
            </div>
        </div>

        <div class="action-buttons">
            <a href="{{ route('produits.edit',$produit->id) }}" class="btn btn-warning">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <form action="{{ route('produits.destroy',$produit->id) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Supprimer
                </button>
            </form>
            <a href="{{ route('produits.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>
        </div>
    </div>
</div>
@endsection
