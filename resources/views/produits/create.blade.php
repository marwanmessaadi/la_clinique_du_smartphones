@extends('layouts.app')

@section('title', 'Nouvelle Commande')

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header d-flex justify-content-between align-items-center bg-primary text-white">
            <h4 class="mb-0">
                <i class="fas fa-cart-plus"></i> Nouvelle Commande d'Achat
            </h4>
            <a href="{{ route('commandes.index') }}" class="btn btn-light">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>

        <div class="card-body">
            {{-- Messages --}}
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> Erreurs de validation</h5>
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            <form action="{{ route('commandes.store') }}" method="POST" id="commandeForm">
                @csrf

                {{-- Fournisseur + notes --}}
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Fournisseur</label>
                        <select name="fournisseur_id" class="form-select @error('fournisseur_id') is-invalid @enderror">
                            <option value="">-- Sélectionner un fournisseur --</option>
                            @foreach($fournisseurs as $fournisseur)
                                <option value="{{ $fournisseur->id }}" {{ old('fournisseur_id') == $fournisseur->id ? 'selected' : '' }}>
                                    {{ $fournisseur->nom }}
                                </option>
                            @endforeach
                        </select>
                        @error('fournisseur_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-bold">Notes (facultatif)</label>
                        <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3" 
                                  placeholder="Notes supplémentaires...">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Section Produits --}}
                <div class="card mb-4 border-primary">
                    <div class="card-header bg-light">
                        <h5 class="mb-0 text-white">
                            <i class="fas fa-boxes"></i> Produits de la commande
                            <span class="badge bg-primary ms-2" id="productCount">0</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        {{-- Actions --}}
                        <div class="mb-3">
                            <button type="button" class="btn btn-primary me-2" onclick="showSearch()">
                                <i class="fas fa-search"></i> Ajouter un produit existant
                            </button>
                            <button type="button" class="btn btn-success" onclick="addNewProduct()">
                                <i class="fas fa-plus"></i> Créer un nouveau produit
                            </button>
                        </div>

                        {{-- Recherche --}}
                        <div id="searchBox" class="mb-3" style="display:none; position:relative">
                            <div class="input-group">
                                <input type="text" id="searchInput" class="form-control" 
                                       placeholder="Rechercher par nom ou code produit...">
                                <button class="btn btn-outline-secondary" type="button" onclick="hideSearch()">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <div id="searchResults" class="list-group position-absolute w-100" 
                                 style="z-index:1000; max-height: 300px; overflow-y: auto; display: none; margin-top: 2px;"></div>
                        </div>

                        {{-- Tableau produits --}}
                        <div class="table-responsive">
                            <table class="table table-bordered table-hover" id="produitsTable">
                                <thead class="table-dark">
                                    <tr>
                                        <th width="25%">Produit</th>
                                        <th width="15%">Catégorie</th>
                                        <th width="10%">Qté</th>
                                        <th width="15%">Prix Achat (DH)</th>
                                        <th width="15%">Prix Vente (DH)</th>
                                        <th width="10%">Total (DH)</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="productsBody">
                                    <tr id="emptyRow">
                                        <td colspan="7" class="text-center py-4 text-muted">
                                            <i class="fas fa-inbox fa-2x mb-2"></i>
                                            <p class="mb-0">Aucun produit ajouté à la commande</p>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="table-light">
                                    <tr>
                                        <th colspan="5" class="text-end">TOTAL DE LA COMMANDE :</th>
                                        <th id="totalGeneral" class="fs-5 text-success">0.00 DH</th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>

                        <div class="alert alert-info mt-3">
                            <i class="fas fa-info-circle"></i>
                            <strong>Information :</strong> Si vous modifiez les prix d'un produit existant, 
                            une nouvelle variante avec un code-barres différent sera automatiquement créée.
                        </div>
                    </div>
                </div>

                {{-- Boutons --}}
                <div class="d-flex justify-content-between mt-4">
                    <a href="{{ route('commandes.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Annuler
                    </a>
                    <button type="submit" class="btn btn-success btn-lg" id="submitBtn">
                        <i class="fas fa-save"></i> Enregistrer la commande
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .price-warning {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 4px 8px;
        margin-top: 4px;
        font-size: 12px;
    }
    .search-result-item:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    .product-row {
        transition: background-color 0.3s;
    }
    .product-row:hover {
        background-color: #f5f5f5;
    }
</style>
@endpush

@push('scripts')
<script>
let index = 0;
let csrfToken = '{{ csrf_token() }}';

/* ---------- RECHERCHE PRODUITS ---------- */
function showSearch() {
    const searchBox = document.getElementById('searchBox');
    searchBox.style.display = 'block';
    document.getElementById('searchInput').focus();
}

function hideSearch() {
    document.getElementById('searchBox').style.display = 'none';
    document.getElementById('searchResults').style.display = 'none';
    document.getElementById('searchInput').value = '';
}

function searchProducts(query) {
    const resultsDiv = document.getElementById('searchResults');
    
    if (query.length < 2) {
        resultsDiv.style.display = 'none';
        resultsDiv.innerHTML = '';
        return;
    }
    
    fetch(`{{ route('commandes.search.products') }}?q=${encodeURIComponent(query)}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (!data || data.length === 0) {
            resultsDiv.innerHTML = `
                <div class="list-group-item text-muted">
                    <i class="fas fa-search me-2"></i>Aucun produit trouvé
                </div>
            `;
            resultsDiv.style.display = 'block';
            return;
        }
        
        let html = '';
        data.forEach(produit => {
            html += `
                <div class="list-group-item list-group-item-action search-result-item" 
                     onclick="addExistingProduct(${produit.id})"
                     style="cursor: pointer;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <strong>${produit.nom}</strong>
                            <div class="text-muted small">
                                <i class="fas fa-tag"></i> ${produit.categorie_nom || 'Non catégorisé'} 
                                | <i class="fas fa-barcode"></i> ${produit.code || 'N/A'}
                            </div>
                        </div>
                        <div class="text-end">
                            <div class="text-primary fw-bold">${produit.stock} en stock</div>
                            <div class="text-success small">${parseFloat(produit.prix_achat).toFixed(2)} DH</div>
                        </div>
                    </div>
                </div>
            `;
        });
        
        resultsDiv.innerHTML = html;
        resultsDiv.style.display = 'block';
    })
    .catch(error => {
        console.error('Erreur de recherche:', error);
        resultsDiv.innerHTML = `
            <div class="list-group-item text-danger">
                <i class="fas fa-exclamation-triangle me-2"></i>
                Erreur lors de la recherche
            </div>
        `;
        resultsDiv.style.display = 'block';
    });
}

/* ---------- AJOUT PRODUIT EXISTANT ---------- */
function addExistingProduct(productId) {
    // CORRECTION: Utilisez la route avec le paramètre correctement
    const url = '{{ route("commandes.get.product", ["id" => "__ID__"]) }}'.replace('__ID__', productId);
    
    fetch(url, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP: ${response.status}`);
        }
        return response.json();
    })
    .then(produit => {
        if (!produit || !produit.id) {
            throw new Error('Données du produit non valides');
        }
        
        removeEmptyRow();
        
        const tbody = document.getElementById('productsBody');
        const row = document.createElement('tr');
        row.id = `row-${index}`;
        row.className = 'product-row';
        
        const prixAchat = parseFloat(produit.prix_achat) || 0;
        const prixVente = parseFloat(produit.prix_vente) || 0;
        const stock = parseInt(produit.stock) || 0;
        
        row.innerHTML = `
            <td>
                <input type="hidden" name="produits[${index}][id]" value="${produit.id}">
                <div class="fw-bold">${produit.nom || ''}</div>
                <div class="text-muted small">
                    <i class="fas fa-barcode"></i> ${produit.code || 'N/A'}
                    <span class="ms-2"><i class="fas fa-warehouse"></i> Stock: ${stock}</span>
                </div>
                <div id="price-warning-${index}" class="price-warning" style="display: none;">
                    <i class="fas fa-exclamation-triangle text-warning"></i>
                    Nouveau code-barres sera créé
                </div>
            </td>
            <td>
                <input type="hidden" name="produits[${index}][categorie_id]" value="${produit.categorie_id || ''}">
                <div>${produit.categorie_nom || ''}</div>
            </td>
            <td>
                <input type="number" name="produits[${index}][quantite]" value="1" min="1"
                       class="form-control form-control-sm qty" 
                       oninput="updateTotal(${index})" required>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" step="0.01" name="produits[${index}][prix_achat]" 
                           value="${prixAchat}"
                           class="form-control pa" 
                           oninput="checkPriceDifference(${index}, ${prixAchat}, ${prixVente})" 
                           required>
                    <span class="input-group-text">DH</span>
                </div>
                <div class="text-muted small mt-1">Original: ${prixAchat.toFixed(2)} DH</div>
            </td>
            <td>
                <div class="input-group input-group-sm">
                    <input type="number" step="0.01" name="produits[${index}][prix_vente]" 
                           value="${prixVente}"
                           class="form-control pv" 
                           oninput="checkPriceDifference(${index}, ${prixAchat}, ${prixVente})" 
                           required>
                    <span class="input-group-text">DH</span>
                </div>
                <div class="text-muted small mt-1">Original: ${prixVente.toFixed(2)} DH</div>
                <input type="hidden" name="produits[${index}][prix_gros]" value="${parseFloat(produit.prix_gros) || 0}">
            </td>
            <td id="line-${index}" class="text-end fw-bold">
                ${prixAchat.toFixed(2)}
            </td>
            <td class="text-center">
                <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${index})" title="Supprimer">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        
        tbody.appendChild(row);
        
        // Fermer la recherche
        hideSearch();
        
        updateProductCount();
        updateTotal(index);
        index++;
    })
    .catch(error => {
        console.error('Erreur détaillée:', error);
        alert(`Erreur lors de l'ajout du produit: ${error.message}`);
    });
}

/* ---------- AJOUT NOUVEAU PRODUIT ---------- */
function addNewProduct() {
    removeEmptyRow();

    const tbody = document.getElementById('productsBody');
    const row = document.createElement('tr');
    row.id = `row-${index}`;
    row.className = 'product-row';

    row.innerHTML = `
        <td>
            <input type="text" name="produits[${index}][nom]" 
                   class="form-control form-control-sm" 
                   placeholder="Nom du produit" required>
        </td>
        <td>
            <select name="produits[${index}][categorie_id]" class="form-select form-select-sm" required>
                <option value="">-- Sélectionner --</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->nom }}</option>
                @endforeach
            </select>
        </td>
        <td>
            <input type="number" name="produits[${index}][quantite]" value="1" min="1"
                   class="form-control form-control-sm qty" oninput="updateTotal(${index})" required>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="produits[${index}][prix_achat]" value="0"
                       class="form-control pa" oninput="updateTotal(${index})" required>
                <span class="input-group-text">DH</span>
            </div>
        </td>
        <td>
            <div class="input-group input-group-sm">
                <input type="number" step="0.01" name="produits[${index}][prix_vente]" value="0"
                       class="form-control" required>
                <span class="input-group-text">DH</span>
            </div>
            <input type="hidden" name="produits[${index}][prix_gros]" value="0">
        </td>
        <td id="line-${index}" class="text-end fw-bold">0.00</td>
        <td class="text-center">
            <button type="button" class="btn btn-danger btn-sm" onclick="removeRow(${index})" title="Supprimer">
                <i class="fas fa-trash"></i>
            </button>
        </td>
    `;

    tbody.appendChild(row);
    updateProductCount();
    index++;
}

/* ---------- VÉRIFICATION DIFFÉRENCE PRIX ---------- */
function checkPriceDifference(i, originalPrixAchat, originalPrixVente) {
    const row = document.getElementById(`row-${i}`);
    if (!row) return;

    const prixAchatInput = row.querySelector('.pa');
    const prixVenteInput = row.querySelector('.pv');
    const warningDiv = document.getElementById(`price-warning-${i}`);
    
    const newPrixAchat = parseFloat(prixAchatInput.value) || 0;
    const newPrixVente = parseFloat(prixVenteInput.value) || 0;
    
    const prixAchatDiff = Math.abs(newPrixAchat - originalPrixAchat) > 0.01;
    const prixVenteDiff = Math.abs(newPrixVente - originalPrixVente) > 0.01;
    
    if (prixAchatDiff || prixVenteDiff) {
        warningDiv.style.display = 'block';
    } else {
        warningDiv.style.display = 'none';
    }
    
    updateTotal(i);
}

/* ---------- CALCUL ---------- */
function updateTotal(i) {
    const row = document.getElementById(`row-${i}`);
    if (!row) return;

    const qInput = row.querySelector('.qty');
    const pInput = row.querySelector('.pa');
    
    const q = parseFloat(qInput.value) || 0;
    const p = parseFloat(pInput.value) || 0;
    const total = q * p;

    document.getElementById(`line-${i}`).innerText = total.toFixed(2);
    updateGlobal();
}

function updateGlobal() {
    let total = 0;
    document.querySelectorAll('[id^="line-"]').forEach(el => {
        total += parseFloat(el.innerText) || 0;
    });
    document.getElementById('totalGeneral').innerText = total.toFixed(2) + ' DH';
}

/* ---------- COMPTEUR PRODUITS ---------- */
function updateProductCount() {
    const count = document.querySelectorAll('#productsBody tr[id^="row-"]').length;
    document.getElementById('productCount').innerText = count;
}

/* ---------- SUPPRESSION ---------- */
function removeRow(i) {
    const row = document.getElementById(`row-${i}`);
    if (row) {
        row.remove();
        updateGlobal();
        updateProductCount();
        
        // Si plus de lignes, afficher le message
        if (document.getElementById('productsBody').children.length === 0) {
            const tbody = document.getElementById('productsBody');
            tbody.innerHTML = `
                <tr id="emptyRow">
                    <td colspan="7" class="text-center py-4 text-muted">
                        <i class="fas fa-inbox fa-2x mb-2"></i>
                        <p class="mb-0">Aucun produit ajouté à la commande</p>
                    </td>
                </tr>
            `;
        }
    }
}

function removeEmptyRow() {
    const empty = document.getElementById('emptyRow');
    if (empty) empty.remove();
}

/* ---------- VALIDATION FORMULAIRE ---------- */
document.getElementById('commandeForm').addEventListener('submit', function(e) {
    const productCount = document.querySelectorAll('#productsBody tr[id^="row-"]').length;
    
    if (productCount === 0) {
        e.preventDefault();
        alert('Veuillez ajouter au moins un produit à la commande.');
        return false;
    }
    
    // Désactiver le bouton pour éviter les doubles clics
    const submitBtn = document.getElementById('submitBtn');
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Enregistrement...';
});

/* ---------- GESTION RECHERCHE ---------- */
document.getElementById('searchInput').addEventListener('keyup', function(e) {
    searchProducts(this.value);
});

// Fermer les résultats quand on clique ailleurs
document.addEventListener('click', function(e) {
    const searchResults = document.getElementById('searchResults');
    const searchInput = document.getElementById('searchInput');
    
    if (searchResults && searchInput && 
        !searchResults.contains(e.target) && 
        !searchInput.contains(e.target) &&
        !e.target.closest('#searchBox')) {
        searchResults.style.display = 'none';
    }
});
</script>
@endpush