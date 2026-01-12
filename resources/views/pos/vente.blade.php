@extends('layouts.app')
@section('title', 'Point de Vente (Vente)')
@section('content')
<div class="container py-4" style="min-height:70vh;">
    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="{{ route('pos.vente') }}" class="btn btn-primary active">Vente Produits</a>
        <a href="{{ route('pos.reparation') }}" class="btn btn-outline-primary">Réparations</a>
    </div>
    <div class="container-fluid py-3" style="min-height:80vh;">
        <div class="row">
            <!-- Colonne gauche : Panier et actions -->
            <div class="col-lg-7 col-12 mb-3">
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-3 d-flex flex-wrap align-items-center gap-2 bg-light rounded-top">
                        <select class="form-select w-auto border-primary" style="max-width:180px;">
                            <option>Walk-In Customer</option>
                        </select>
                        <button class="btn btn-primary btn-sm rounded-circle d-flex align-items-center justify-content-center" title="Ajouter client"><i class="bi bi-person-plus"></i></button>
                        <div class="input-group flex-fill">
                            <input type="text" id="search-produit" class="form-control border-primary" placeholder="Rechercher produit, code-barres...">
                            <button class="btn btn-outline-primary" id="btn-search-produit" type="button"><i class="bi bi-search"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body p-0 bg-white rounded">
                        <table class="table mb-0 align-middle table-hover">
                            <thead class="table-primary">
                                <tr>
                                    <th>Produit</th>
                                    <th>Qté</th>
                                    <th>Prix</th>
                                    <th>Total</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="panier-body">
                                <!-- Dynamique JS -->
                                <tr><td colspan="5" class="text-center text-muted">Aucun produit</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col">
                        <div class="bg-white rounded p-2 shadow-sm">Articles: <span id="nb-articles" class="fw-bold">0</span></div>
                    </div>
                    <div class="col">
                        <div class="bg-white rounded p-2 shadow-sm">Total: <span id="total-panier" class="fw-bold text-primary">0.00</span></div>
                    </div>
                </div>
                <div class="d-flex gap-2 flex-wrap mb-3">
                    <button class="btn btn-success w-100" id="btn-valider-vente"><i class="bi bi-check-circle"></i> Valider la vente & Imprimer facture</button>
                </div>
                <div class="text-end bg-white rounded p-2 shadow-sm">
                    <span class="fs-5">Total à payer: </span><span id="total-payer" class="fw-bold fs-4 text-success">0.00</span>
                </div>
            </div>
            <!-- Colonne droite : Produits -->
            <div class="col-lg-5 col-12">
                <div class="d-flex gap-2 mb-3">
                    <select class="form-select w-auto border-primary">
                        <option>Toutes catégories</option>
                    </select>
                    <select class="form-select w-auto border-primary">
                        <option>Toutes les marques</option>
                    </select>
                </div>
                <div class="row row-cols-2 row-cols-md-3 g-3" id="grille-produits" style="max-height:70vh;overflow-y:auto;">
                    @foreach($produits as $produit)
                    <div class="col produit-item" data-nom="{{ strtolower($produit->nom) }}" data-code="{{ strtolower($produit->code) }}">
                        <div class="card h-100 text-center border-0 shadow-sm product-tile position-relative">
                            <div class="card-body p-2">
                                <img src="{{ $produit->image_url ?? 'https://via.placeholder.com/80x80?text=Image' }}" class="mb-2 rounded bg-light" style="width:80px;height:80px;object-fit:contain;">
                                <div class="fw-bold small text-primary">{{ $produit->nom }}</div>
                                <div class="text-muted small">({{ $produit->code }})</div>
                                <div class="badge bg-light text-dark border position-absolute top-0 end-0 m-1">{{ number_format($produit->prix_vente,2) }} DH</div>
                                <button class="btn btn-success btn-sm mt-2 btn-add-panier" data-id="{{ $produit->id }}" data-nom="{{ $produit->nom }}" data-prix="{{ $produit->prix_vente }}"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <script>
    // CSRF Laravel
    function getCsrfToken() {
        return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;
    }

    // Gestion du panier en JS
    let panier = [];

    function updatePanierUI() {
        const tbody = document.getElementById('panier-body');
        tbody.innerHTML = '';
        let total = 0;
        if (panier.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun produit</td></tr>';
        } else {
            panier.forEach((item, idx) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${item.nom}</td>
                    <td><input type="number" min="1" value="${item.qte}" class="form-control form-control-sm qte-panier" data-idx="${idx}" style="width:60px;"></td>
                    <td>${item.prix.toFixed(2)} DH</td>
                    <td>${(item.prix * item.qte).toFixed(2)} DH</td>
                    <td><button class="btn btn-sm btn-danger btn-remove-panier" data-idx="${idx}"><i class="bi bi-trash"></i></button></td>
                `;
                tbody.appendChild(row);
                total += item.prix * item.qte;
            });
        }
        document.getElementById('nb-articles').textContent = panier.reduce((a, b) => a + b.qte, 0);
        document.getElementById('total-panier').textContent = total.toFixed(2);
        document.getElementById('total-payer').textContent = total.toFixed(2);
    }

    // Ajouter produit au panier
    document.querySelectorAll('.btn-add-panier').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const prix = parseFloat(this.dataset.prix);
            const exist = panier.find(p => p.id == id);
            if (exist) {
                exist.qte++;
            } else {
                panier.push({ id, nom, prix, qte: 1 });
            }
            updatePanierUI();
        });
    });

    // Modifier quantité ou supprimer produit
    document.getElementById('panier-body').addEventListener('input', function(e) {
        if (e.target.classList.contains('qte-panier')) {
            const idx = e.target.dataset.idx;
            let val = parseInt(e.target.value);
            if (val < 1) val = 1;
            panier[idx].qte = val;
            updatePanierUI();
        }
    });
    document.getElementById('panier-body').addEventListener('click', function(e) {
        if (e.target.classList.contains('btn-remove-panier')) {
            const idx = e.target.dataset.idx;
            panier.splice(idx, 1);
            updatePanierUI();
        }
    });

    // Valider la vente
    document.getElementById('btn-valider-vente').onclick = function() {
        if (panier.length === 0) {
            alert('Le panier est vide !');
            return;
        }
        fetch('/pos/cash', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': getCsrfToken(),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ panier: panier })
        })
        .then(r => r.json())
        .then(res => {
            if(res.success && res.facture_url) {
                window.open(res.facture_url, '_blank');
                setTimeout(() => { window.print(); }, 1000);
            } else {
                alert(res.message || 'Erreur lors de la validation.');
            }
        })
        .catch(() => alert('Erreur serveur'));
    };

    // Initialiser l'affichage panier
    updatePanierUI();
    </script>
</div>
@endsection
