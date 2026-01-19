@extends('layouts.app')
@section('title', 'Point de Vente')

@push('styles')
<style>
.pos-container { background: #f8f9fa; min-height: 100vh; }
.pos-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1rem; border-radius: 10px; margin-bottom: 1.5rem; }
.product-tile { cursor: pointer; transition: all 0.3s ease; border: 2px solid transparent; }
.product-tile:hover { transform: translateY(-5px); box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3); border-color: #667eea; }
.panier-card { background: white; border-radius: 10px; box-shadow: 0 5px 15px rgba(0,0,0,0.1); }
.panier-empty { padding: 2rem; text-align: center; color: #6c757d; }
.btn-add-cart { background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%); border: none; color: white; transition: all 0.3s; }
.btn-add-cart:hover { transform: scale(1.05); box-shadow: 0 5px 15px rgba(17, 153, 142, 0.4); }
.total-section { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 1.5rem; border-radius: 10px; margin-top: 1rem; }
.product-grid { max-height: 70vh; overflow-y: auto; }
.product-grid::-webkit-scrollbar { width: 8px; }
.product-grid::-webkit-scrollbar-track { background: #f1f1f1; }
.product-grid::-webkit-scrollbar-thumb { background: #667eea; border-radius: 4px; }
.badge-stock { position: absolute; top: 5px; left: 5px; background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 5px; font-size: 0.75rem; }
.product-price { position: absolute; top: 5px; right: 5px; background: #38ef7d; color: white; padding: 0.25rem 0.75rem; border-radius: 20px; font-weight: bold; }
</style>
@endpush

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="pos-container">
    <div class="container-fluid py-3">
        <!-- En-tête POS -->
        <div class="pos-header">
            <div class="d-flex justify-content-between align-items-center">
                <h2 class="mb-0"><i class="fas fa-cash-register"></i> Point de Vente</h2>
                <div class="d-flex gap-2">
                    <a href="{{ route('pos.vente') }}" class="btn btn-light active">
                        <i class="fas fa-shopping-cart"></i> Vente Produits
                    </a>
                    <a href="{{ route('pos.reparation') }}" class="btn btn-outline-light">
                        <i class="fas fa-tools"></i> Réparations
                    </a>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- COLONNE GAUCHE : PANIER -->
            <div class="col-lg-7 mb-4">
                <!-- Barre de recherche et client -->
                <div class="card mb-3 panier-card">
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-md-4">
                                <select class="form-select" id="select-client">
                                    <option value="">Walk-In Customer</option>
                                    @foreach($clients ?? [] as $client)
                                        <option value="{{ $client->id }}">{{ $client->prenom }} {{ $client->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-8">
                                <div class="input-group">
                                    <input type="text" id="search-produit" class="form-control" placeholder="Rechercher par nom ou code-barres...">
                                    <button class="btn btn-primary" type="button">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tableau du panier -->
                <div class="card panier-card mb-3">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-shopping-cart"></i> Panier</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 20%;">Produit</th>
                                        <th style="width: 15%;" class="text-center">Qté</th>
                                        <th style="width: 20%;" class="text-end">Prix d'achat</th>
                                        <th style="width: 20%;" class="text-end">Prix Unit</th>
                                        <th style="width: 5%;"></th>
                                    </tr>
                                </thead>
                                <tbody id="panier-body">
                                    <tr class="panier-empty">
                                        <td colspan="6">
                                            <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                                            <p class="mb-0">Panier vide</p>
                                            <small>Sélectionnez des produits pour commencer</small>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Résumé et total -->
                <div class="row g-2 mb-3">
                    <div class="col-6">
                        <div class="card text-center">
                            <div class="card-body py-2">
                                <small class="text-muted">Articles</small>
                                <h4 class="mb-0" id="nb-articles">0</h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card text-center">
                            <div class="card-body py-2">
                                <small class="text-muted">Sous-total</small>
                                <h4 class="mb-0 text-primary" id="total-panier">0.00 DH</h4>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total à payer -->
                <div class="total-section">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h3 class="mb-0">TOTAL À PAYER</h3>
                        <h2 class="mb-0" id="total-payer">0.00 DH</h2>
                    </div>
                    <button class="btn btn-success btn-lg w-100" id="btn-valider-vente">
                        <i class="fas fa-check-circle"></i> Valider la Vente
                    </button>
                </div>
            </div>

            <!-- COLONNE DROITE : PRODUITS -->
            <div class="col-lg-5">
                <!-- Filtres -->
                <div class="card panier-card mb-3">
                    <div class="card-body p-3">
                        <div class="row g-2">
                            <div class="col-12">
                                <select class="form-select" id="filter-categorie">
                                    <option value="">Toutes les catégories</option>
                                    @foreach($categories ?? [] as $categorie)
                                        <option value="{{ $categorie->id }}">{{ $categorie->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Grille des produits -->
                <div class="product-grid">
                    <div class="row row-cols-2 g-3" id="grille-produits">
                        @forelse($produits as $produit)
                        <div class="col produit-item" 
                             data-nom="{{ strtolower($produit->nom) }}" 
                             data-code="{{ strtolower($produit->code ?? '') }}"
                             data-categorie="{{ $produit->categorie_id }}">
                            <div class="card product-tile h-100 position-relative">
                                <span class="badge-stock">Stock: {{ $produit->quantite }}</span>
                                <span class="product-price">{{ number_format($produit->prix_vente, 2) }} DH</span>
                                <div class="card-body text-center p-2">
                                    <img src="{{ $produit->image ? asset('storage/' . $produit->image) : 'https://via.placeholder.com/100x100?text=Produit' }}" 
                                         class="mb-2 rounded" 
                                         style="width:100px;height:100px;object-fit:cover;">
                                    <h6 class="card-title mb-1">{{ $produit->nom }}</h6>
                                    <small class="text-muted">{{ $produit->code }}</small>
                                    <button class="btn btn-add-cart btn-sm w-100 mt-2" 
                                            data-id="{{ $produit->id }}" 
                                            data-nom="{{ $produit->nom }}" 
                                            data-prix="{{ $produit->prix_vente }}"
                                            data-prix-achat="{{ $produit->prix_achat }}"
                                            data-stock="{{ $produit->quantite }}"
                                            {{ $produit->quantite <= 0 ? 'disabled' : '' }}>
                                        <i class="fas fa-cart-plus"></i> Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="col-12">
                            <div class="alert alert-info text-center">
                                <i class="fas fa-info-circle"></i> Aucun produit disponible
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let panier = [];
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '';

    function updatePanierUI() {
        const tbody = document.getElementById('panier-body');
        tbody.innerHTML = '';
        if (panier.length === 0) {
            tbody.innerHTML = `<tr class="panier-empty"><td colspan="6">
                <i class="fas fa-shopping-cart fa-3x mb-3"></i>
                <p class="mb-0">Panier vide</p>
                <small>Sélectionnez des produits pour commencer</small>
            </td></tr>`;
        } else {
            let total = 0;
            panier.forEach((item, idx) => {
                const sousTotal = item.prix * item.qte;
                total += sousTotal;
                tbody.insertAdjacentHTML('beforeend', `
                    <tr>
                        <td><strong>${item.nom}</strong><br><small class="text-muted">${item.prix.toFixed(2)} DH × ${item.qte}</small></td>
                        <td class="text-center">
                            <div class="input-group input-group-sm" style="width: 100px; margin: auto;">
                                <button class="btn btn-outline-secondary btn-decrease" data-idx="${idx}" type="button">-</button>
                                <input type="number" class="form-control text-center qte-input" value="${item.qte}" min="1" max="${item.stock}" data-idx="${idx}">
                                <button class="btn btn-outline-secondary btn-increase" data-idx="${idx}" type="button">+</button>
                            </div>
                        </td>
                        <td class="text-end">
                            <input type="number" class="form-control form-control-sm prix-achat-input text-end" value="${item.prixAchat?.toFixed(2) || '0.00'}" min="0" step="0.01" data-idx="${idx}">
                        </td>
                        <td class="text-end">
                            <input type="number" class="form-control form-control-sm prix-input text-end" value="${item.prix.toFixed(2)}" min="0" step="0.01" data-idx="${idx}">
                        </td>
                        
                        <td class="text-center"><button class="btn btn-sm btn-danger btn-remove" data-idx="${idx}"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `);
            });
            document.getElementById('nb-articles').textContent = panier.reduce((sum, item) => sum + item.qte, 0);
            document.getElementById('total-panier').textContent = total.toFixed(2) + ' DH';
            document.getElementById('total-payer').textContent = total.toFixed(2) + ' DH';
        }
        attachPanierEvents();
    }

    function attachPanierEvents() {
        document.querySelectorAll('.btn-increase').forEach(btn => btn.addEventListener('click', function() {
            const idx = parseInt(this.dataset.idx);
            if (panier[idx].qte < panier[idx].stock) panier[idx].qte++;
            else alert('Stock insuffisant !');
            updatePanierUI();
        }));

        document.querySelectorAll('.btn-decrease').forEach(btn => btn.addEventListener('click', function() {
            const idx = parseInt(this.dataset.idx);
            if (panier[idx].qte > 1) panier[idx].qte--;
            updatePanierUI();
        }));

        document.querySelectorAll('.qte-input').forEach(input => input.addEventListener('change', function() {
            const idx = parseInt(this.dataset.idx);
            let val = parseInt(this.value); if (isNaN(val) || val < 1) val = 1;
            if (val > panier[idx].stock) { val = panier[idx].stock; alert('Quantité limitée au stock disponible'); }
            panier[idx].qte = val; updatePanierUI();
        }));
        
        document.querySelectorAll('.prix-achat-input').forEach(input => input.addEventListener('change', function() {
            const idx = parseInt(this.dataset.idx);
            let val = parseFloat(this.value); if (isNaN(val) || val < 0) val = 0;
            panier[idx].prixAchat = val; updatePanierUI();
        }));

        document.querySelectorAll('.prix-input').forEach(input => input.addEventListener('change', function() {
            const idx = parseInt(this.dataset.idx);
            let val = parseFloat(this.value); if (isNaN(val) || val < 0) val = 0;
            panier[idx].prix = val; updatePanierUI();
        }));

        document.querySelectorAll('.btn-remove').forEach(btn => btn.addEventListener('click', function() {
            const idx = parseInt(this.dataset.idx);
            panier.splice(idx, 1); updatePanierUI();
        }));
    }

    document.querySelectorAll('.btn-add-cart').forEach(btn => btn.addEventListener('click', function() {
        const id = this.dataset.id, nom = this.dataset.nom, prix = parseFloat(this.dataset.prix), prixAchat = parseFloat(this.dataset.prixAchat), stock = parseInt(this.dataset.stock);
        const existant = panier.find(item => item.id == id);
        if (existant) { if (existant.qte < stock) existant.qte++; else { alert('Stock insuffisant !'); return; } }
        else panier.push({ id, nom, prix, prixAchat, qte: 1, stock });
        updatePanierUI();
    }));

    document.getElementById('search-produit').addEventListener('input', function() {
        const search = this.value.toLowerCase();
        document.querySelectorAll('.produit-item').forEach(item => {
            const nom = item.dataset.nom, code = item.dataset.code;
            item.style.display = (nom.includes(search) || code.includes(search)) ? '' : 'none';
        });
    });

    document.getElementById('filter-categorie')?.addEventListener('change', function() {
        const catId = this.value;
        document.querySelectorAll('.produit-item').forEach(item => {
            item.style.display = (!catId || item.dataset.categorie == catId) ? '' : 'none';
        });
    });

    document.getElementById('btn-valider-vente').addEventListener('click', function() {
        if (panier.length === 0) { alert('Le panier est vide !'); return; }
        const clientId = document.getElementById('select-client').value;
        const produits = panier.map(item => ({ id: item.id, quantite: item.qte, prix_unitaire: item.prix }));
        
        fetch('{{ route("pos.storeVente") }}', {
            method: 'POST', 
            headers: { 
                'Content-Type': 'application/json', 
                'X-CSRF-TOKEN': csrfToken, 
                'Accept': 'application/json' 
            },
            body: JSON.stringify({ produits: produits, client_id: clientId || null })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur serveur');
            }
            return response.text();
        })
        .then(html => {
            const printWindow = window.open('', '_blank'); 
            printWindow.document.write(html); 
            printWindow.document.close();
            panier = []; 
            updatePanierUI(); 
            alert('Vente enregistrée avec succès !');
        })
        .catch(error => { 
            console.error('Erreur:', error); 
            alert('Erreur lors de la validation de la vente'); 
        });
    });

    updatePanierUI();
});
</script>
@endpush