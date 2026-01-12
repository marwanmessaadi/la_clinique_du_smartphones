@extends('layouts.app')

@section('title', 'Liste des Produits')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">Liste des Produits</h1>
        <div class="btn-group">
            <a href="{{ route('produits.create', ['type' => 'achat']) }}" class="btn btn-success">
                <i class="fas fa-plus"></i> Produit Achat
            </a>
            <a href="{{ route('ventes.create') }}" class="btn btn-primary">
                <i class="fas fa-plus"></i> Produit Vente
            </a>
        </div>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <!-- Products Table Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">Liste des Produits</h6>
            <div class="btn-group">
                <button type="button" class="btn btn-sm btn-outline-primary" onclick="exportToExcel()">
                    <i class="fas fa-file-excel"></i> Exporter
                </button>
                <button type="button" class="btn btn-sm btn-outline-secondary" onclick="printTable()">
                    <i class="fas fa-print"></i> Imprimer
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-hover" id="productsTable">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Description</th>
                            <th>Prix d'achat</th>
                            <th>Prix de vente</th>
                            <th>Prix de gros</th>
                            <th>Catégorie</th>
                            <th>Type</th>
                            <th>Stock</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($produits as $produit)
                            <tr>
                                <td>{{ $produit->nom }}</td>
                                <td>{{ Str::limit($produit->description, 50) }}</td>
                                <td class="price-column">{{ number_format($produit->prix_achat, 2) }} DH</td>
                                <td class="price-column">{{ number_format($produit->prix_vente, 2) }} DH</td>
                                <td class="price-column">{{ $produit->prix_gros ? number_format($produit->prix_gros, 2) . ' DH' : '-' }}</td>
                                <td>{{ $produit->categorie?->nom ?? 'Non renseignée' }}</td>
                                <td>
                                    <span class="badge badge-{{ $produit->type }}">
                                        {{ ucfirst($produit->type) }}
                                    </span>
                                </td>
                                <td class="text-center">
                                    @if($produit->quantite == 0)
                                        <span class="badge badge-indisponible">Indisponible</span>
                                    @else
                                        <span class="fw-bold">{{ $produit->quantite }}</span>
                                    @endif
                                </td>
                                <td class="btn-group-actions">
                                    <div class="btn-group btn-group-sm">
                                        <a href="{{ route('produits.edit', $produit->id) }}" 
                                           class="btn btn-warning" 
                                           title="Modifier">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('produits.show', $produit->id) }}" 
                                           class="btn btn-info" 
                                           title="Voir détails">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <form action="{{ route('produits.destroy', $produit->id) }}" 
                                              method="POST" 
                                              class="d-inline"
                                              onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce produit ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger" title="Supprimer">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center py-4">
                                    Aucun produit trouvé
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function exportToExcel() {
        let table = document.getElementById("productsTable");
        let html = table.outerHTML;
        let url = 'data:application/vnd.ms-excel,' + encodeURIComponent(html);
        let downloadLink = document.createElement("a");
        document.body.appendChild(downloadLink);
        downloadLink.href = url;
        downloadLink.download = 'produits.xls';
        downloadLink.click();
        document.body.removeChild(downloadLink);
    }

    function printTable() {
        window.print();
    }

    // Auto-hide alerts after 5 seconds
    document.addEventListener('DOMContentLoaded', function() {
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(function(alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    });
</script>
@endpush
