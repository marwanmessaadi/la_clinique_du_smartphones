@extends('layouts.app')
@section('title', 'Nouvelle Vente')

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const produitSelect = document.getElementById('produit_id');
    const quantiteInput = document.getElementById('quantite');
    const prixUnitaireInput = document.getElementById('prix_unitaire');
    const productInfo = document.querySelector('.product-info');
    const totalSection = document.querySelector('.total-section');
    const totalAmount = document.querySelector('.total-amount');
    const submitBtn = document.querySelector('.btn-submit');

    let selectedProduct = null;

    // Charger les informations du produit sélectionné
    produitSelect.addEventListener('change', function() {
        const produitId = this.value;
        if (produitId) {
            fetch(`/api/produits/${produitId}`)
                .then(response => response.json())
                .then(data => {
                    selectedProduct = data;
                    updateProductInfo();
                    updateTotal();
                })
                .catch(error => {
                    console.error('Erreur lors du chargement du produit:', error);
                });
        } else {
            selectedProduct = null;
            productInfo.style.display = 'none';
            totalSection.style.display = 'none';
        }
    });

    // Écouteurs pour mettre à jour le total
    quantiteInput.addEventListener('input', updateTotal);
    prixUnitaireInput.addEventListener('input', updateTotal);

    // Mettre à jour l'affichage des informations du produit
    function updateProductInfo() {
        if (selectedProduct) {
            document.querySelector('.product-stock').textContent = 'Stock disponible: ' + selectedProduct.quantite;

            const stockWarning = document.querySelector('.stock-warning');
            if (selectedProduct.quantite <= 0) {
                stockWarning.textContent = 'Produit en rupture de stock!';
                stockWarning.style.display = 'block';
                // submitBtn.disabled = true;
            } else {
                stockWarning.style.display = 'none';
                // submitBtn.disabled = false;
            }

            productInfo.style.display = 'block';
        }
    }

    // Mettre à jour le total
    function updateTotal() {
        const quantite = parseInt(quantiteInput.value) || 0;
        const prixUnitaire = parseFloat(prixUnitaireInput.value) || 0;
        
        if (quantite > 0 && prixUnitaire > 0) {
            const total = prixUnitaire * quantite;
            totalAmount.textContent = total.toFixed(2) + ' €';
            totalSection.style.display = 'block';

            // Vérifier si la quantité demandée est disponible (si un produit est sélectionné)
            if (selectedProduct && quantite > selectedProduct.quantite) {
                document.querySelector('.stock-warning').textContent = 'Quantité demandée supérieure au stock disponible!';
                document.querySelector('.stock-warning').style.display = 'block';
                // submitBtn.disabled = true;
            } else {
                document.querySelector('.stock-warning').style.display = 'none';
                // submitBtn.disabled = false;
            }
        } else {
            totalSection.style.display = 'none';
        }
    }

    // Écouter les changements de quantité
    // quantiteInput.addEventListener('input', updateTotal); // Déjà ajouté plus haut
});
</script>
@endpush

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Nouvelle Vente</h1>
                    <p class="form-subtitle">Enregistrez une nouvelle vente de produit</p>
                </div>

                <div class="form-body">
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('ventes.store') }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="produit_id">Produit *</label>
                                    <select class="form-control" id="produit_id" name="produit_id" required>
                                        <option value="">Sélectionnez un produit</option>
                                        @foreach($produits as $produit)
                                            <option value="{{ $produit->id }}" data-prix="{{ $produit->prix_vente }}" data-stock="{{ $produit->quantite }}">
                                                {{ $produit->nom }} - {{ $produit->prix_vente }} €
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="quantite">Quantité *</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite" min="1" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="prix_unitaire">Prix unitaire *</label>
                                    <input type="number" class="form-control" id="prix_unitaire" name="prix_unitaire" step="0.01" min="0" required>
                                </div>
                            </div>
                        </div>

                        <!-- Informations du produit -->
                        <div class="product-info">
                            <div class="row">
                                <div class="col-md-6">
                                    <span class="product-stock"></span>
                                </div>
                            </div>
                            <div class="stock-warning" style="display: none;"></div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="utilisateur_id">Client</label>
                                    <select class="form-control" id="utilisateur_id" name="utilisateur_id">
                                        <option value="">Client anonyme</option>
                                        @foreach($clients as $client)
                                            <option value="{{ $client->id }}">
                                                {{ $client->prenom }} {{ $client->nom }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <small class="text-muted">Optionnel - laissez vide pour un client anonyme</small>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="statut">Statut *</label>
                                    <select class="form-control" id="statut" name="statut" required>
                                        <option value="en_cours">En cours</option>
                                        <option value="finalisee">Finalisée</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="form-label" for="notes">Notes</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Notes supplémentaires sur la vente..."></textarea>
                        </div>

                        <!-- Section total -->
                        <div class="total-section" style="display: none;">
                            <h4>Total de la vente</h4>
                            <div class="total-amount">0.00 €</div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-plus"></i> Créer la vente
                            </button>
                            <a href="{{ route('ventes.index') }}" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
