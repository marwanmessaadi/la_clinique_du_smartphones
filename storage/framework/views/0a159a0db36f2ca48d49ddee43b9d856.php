
<?php $__env->startSection('title', 'Point de Vente (POS)'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4" style="min-height:70vh;">
    <div class="d-flex justify-content-center mb-4 gap-3">
        <button id="btn-ventes" class="btn btn-primary active" type="button">Vente Produits</button>
        <button id="btn-reparations" class="btn btn-outline-primary" type="button">Réparations</button>
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
                    <button class="btn btn-outline-info" id="btn-brouillon"><i class="bi bi-pencil-square"></i> Brouillon</button>
                    <button class="btn btn-warning" id="btn-citation"><i class="bi bi-file-earmark-text"></i> Citation</button>
                    <button class="btn btn-danger" id="btn-suspendre"><i class="bi bi-pause-circle"></i> Suspendre</button>
                    <button class="btn btn-primary" id="btn-credit"><i class="bi bi-credit-card-2-back"></i> Crédit</button>
                    <button class="btn btn-dark" id="btn-multiple"><i class="bi bi-wallet2"></i> Paiement multiple</button>
                    <button class="btn btn-success" id="btn-especes"><i class="bi bi-cash"></i> Espèces</button>
                    <button class="btn btn-outline-danger" id="btn-annuler"><i class="bi bi-x-circle"></i> Annuler</button>
                    <button class="btn btn-outline-primary" id="btn-transactions"><i class="bi bi-clock-history"></i> Transactions</button>
                </div>
                </script>
                <script>
                // Bouton Annuler : vide le panier
                document.getElementById('btn-annuler').addEventListener('click', function() {
                    if(confirm('Vider le panier ?')) {
                        panier = [];
                        majPanier();
                    }
                });
                // Hooks pour les autres boutons (à relier à Laravel)
                document.getElementById('btn-brouillon').addEventListener('click', function() {
                    if(panier.length === 0) { alert('Le panier est vide.'); return; }
                    fetch("<?php echo e(route('pos.draft')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({ panier })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            alert('Brouillon enregistré avec succès !');
                            panier = [];
                            majPanier();
                        } else {
                            alert('Erreur lors de l\'enregistrement du brouillon.');
                        }
                    })
                    .catch(()=>alert('Erreur de communication avec le serveur.'));
                });
                                document.getElementById('btn-citation').addEventListener('click', function() {
                                        if(panier.length === 0) { alert('Le panier est vide.'); return; }
                                        fetch("<?php echo e(route('pos.quote')); ?>", {
                                                method: 'POST',
                                                headers: {
                                                        'Content-Type': 'application/json',
                                                        'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                                                },
                                                body: JSON.stringify({ panier })
                                        })
                                        .then(r => r.json())
                                        .then(res => {
                                                if(res.success) {
                                                        alert('Devis enregistré avec succès !');
                                                        panier = [];
                                                        majPanier();
                                                } else {
                                                        alert('Erreur lors de l\'enregistrement du devis.');
                                                }
                                        })
                                        .catch(()=>alert('Erreur de communication avec le serveur.'));
                                });

                                // Recherche produit dynamique
                                document.getElementById('btn-search-produit').addEventListener('click', filtrerProduits);
                                document.getElementById('search-produit').addEventListener('keyup', function(e){ if(e.key==='Enter'){filtrerProduits();} });
                                function filtrerProduits() {
                                    const val = document.getElementById('search-produit').value.trim().toLowerCase();
                                    document.querySelectorAll('.produit-item').forEach(function(div) {
                                        const nom = div.dataset.nom;
                                        const code = div.dataset.code;
                                        if(val === '' || nom.includes(val) || code.includes(val)) {
                                            div.style.display = '';
                                        } else {
                                            div.style.display = 'none';
                                        }
                                    });
                                }
                document.getElementById('btn-suspendre').addEventListener('click', function() {
                    if(panier.length === 0) { alert('Le panier est vide.'); return; }
                    fetch("<?php echo e(route('pos.suspend')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({ panier })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            alert('Vente suspendue avec succès !');
                            panier = [];
                            majPanier();
                        } else {
                            alert('Erreur lors de la suspension de la vente.');
                        }
                    })
                    .catch(()=>alert('Erreur de communication avec le serveur.'));
                });
                document.getElementById('btn-credit').addEventListener('click', function() {
                    if(panier.length === 0) { alert('Le panier est vide.'); return; }
                    fetch("<?php echo e(route('pos.credit')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({ panier })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            alert('Vente à crédit enregistrée avec succès !');
                            panier = [];
                            majPanier();
                        } else {
                            alert('Erreur lors de l\'enregistrement de la vente à crédit.');
                        }
                    })
                    .catch(()=>alert('Erreur de communication avec le serveur.'));
                });
                document.getElementById('btn-multiple').addEventListener('click', function() {
                    if(panier.length === 0) { alert('Le panier est vide.'); return; }
                    fetch("<?php echo e(route('pos.multiple')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({ panier })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            alert('Vente paiement multiple enregistrée avec succès !');
                            panier = [];
                            majPanier();
                        } else {
                            alert('Erreur lors de l\'enregistrement du paiement multiple.');
                        }
                    })
                    .catch(()=>alert('Erreur de communication avec le serveur.'));
                });
                document.getElementById('btn-especes').addEventListener('click', function() {
                    if(panier.length === 0) { alert('Le panier est vide.'); return; }
                    fetch("<?php echo e(route('pos.cash')); ?>", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('input[name=_token]')?.value || '<?php echo e(csrf_token()); ?>'
                        },
                        body: JSON.stringify({ panier })
                    })
                    .then(r => r.json())
                    .then(res => {
                        if(res.success) {
                            alert('Vente en espèces enregistrée avec succès !');
                            panier = [];
                            majPanier();
                        } else {
                            alert('Erreur lors de l\'enregistrement de la vente.');
                        }
                    })
                    .catch(()=>alert('Erreur de communication avec le serveur.'));
                });
                document.getElementById('btn-transactions').addEventListener('click', function() {
                    alert('Affichage des transactions récentes à implémenter');
                });
                </script>
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
                    <?php $__currentLoopData = $produits; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $produit): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="col produit-item" data-nom="<?php echo e(strtolower($produit->nom)); ?>" data-code="<?php echo e(strtolower($produit->code)); ?>">
                        <div class="card h-100 text-center border-0 shadow-sm product-tile position-relative">
                            <div class="card-body p-2">
                                <img src="<?php echo e($produit->image_url ?? 'https://via.placeholder.com/80x80?text=Image'); ?>" class="mb-2 rounded bg-light" style="width:80px;height:80px;object-fit:contain;">
                                <div class="fw-bold small text-primary"><?php echo e($produit->nom); ?></div>
                                <div class="text-muted small">(<?php echo e($produit->code); ?>)</div>
                                <div class="badge bg-light text-dark border position-absolute top-0 end-0 m-1"><?php echo e(number_format($produit->prix_vente,2)); ?> DH</div>
                                <button class="btn btn-success btn-sm mt-2 btn-add-panier" data-id="<?php echo e($produit->id); ?>" data-nom="<?php echo e($produit->nom); ?>" data-prix="<?php echo e($produit->prix_vente); ?>"><i class="bi bi-cart-plus"></i> Ajouter</button>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
    <script>
    // Panier JS (démonstration, à relier à l’API Laravel pour persistance)
    let panier = [];
    function majPanier() {
        const tbody = document.getElementById('panier-body');
        tbody.innerHTML = '';
        let total = 0;
        if(panier.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun produit</td></tr>';
        } else {
            panier.forEach((item, idx) => {
                const tr = document.createElement('tr');
                tr.innerHTML = `<td>${item.nom}</td><td><input type='number' min='1' value='${item.qte}' class='form-control form-control-sm input-qte' data-idx='${idx}' style='width:60px;'></td><td>${item.prix.toFixed(2)} DH</td><td>${(item.qte*item.prix).toFixed(2)} DH</td><td><button class='btn btn-sm btn-danger btn-del' data-idx='${idx}'><i class='bi bi-trash'></i></button></td>`;
                tbody.appendChild(tr);
                total += item.qte * item.prix;
            });
        }
        document.getElementById('nb-articles').textContent = panier.reduce((a,b)=>a+b.qte,0);
        document.getElementById('total-panier').textContent = total.toFixed(2);
        document.getElementById('total-payer').textContent = total.toFixed(2);
    }
    document.querySelectorAll('.btn-add-panier').forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.dataset.id;
            const nom = this.dataset.nom;
            const prix = parseFloat(this.dataset.prix);
            let found = panier.find(p => p.id === id);
            if(found) { found.qte += 1; } else { panier.push({id, nom, prix, qte:1}); }
            majPanier();
        });
    });
    document.getElementById('panier-body').addEventListener('click', function(e) {
        if(e.target.closest('.btn-del')) {
            const idx = e.target.closest('.btn-del').dataset.idx;
            panier.splice(idx,1); majPanier();
        }
    });
    document.getElementById('panier-body').addEventListener('change', function(e) {
        if(e.target.classList.contains('input-qte')) {
            const idx = e.target.dataset.idx;
            panier[idx].qte = parseInt(e.target.value)||1;
            majPanier();
        }
    });
    </script>

        <!-- Section Réparations -->
        <div class="mt-4">
            <div class="d-flex justify-content-between align-items-center mb-2">
                <h5 class="mb-0">Réparations</h5>
                <form method="GET" action="<?php echo e(route('pos.index')); ?>">
                    <button type="submit" name="show_repair_form" value="1" class="btn btn-outline-primary btn-sm"><i class="bi bi-wrench"></i> Ajouter réparation</button>
                </form>
            </div>
            <?php if(request('show_repair_form')): ?>
            <div class="card card-body mb-3 border-primary">
                <form id="form-add-reparation" method="POST" action="<?php echo e(route('pos.storeRepair')); ?>">
                    <?php echo csrf_field(); ?>
                    <div class="row g-2 mb-2">
                        <div class="col-md-4">
                            <input type="text" name="nom" class="form-control" placeholder="Client" required>
                        </div>
                        <div class="col-md-4">
                            <input type="text" name="produit" class="form-control" placeholder="Produit à réparer" required>
                        </div>
                        <div class="col-md-4">
                            <input type="number" name="prix" class="form-control" placeholder="Prix" step="0.01" required>
                        </div>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-2">
                        <select name="etat" class="form-select">
                            <option value="en_cours">En cours</option>
                            <option value="terminee">Terminée</option>
                        </select>
                    </div>
                    <div class="text-end">
                        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
            <?php endif; ?>
            </div>
            <!-- JS dynamique pour panier et réparation -->
            <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Ajout dynamique au panier
                document.querySelectorAll('.form-add-panier').forEach(function(form) {
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const data = new FormData(form);
                        fetch("<?php echo e(route('pos.store')); ?>", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                            },
                            body: data
                        })
                        .then(r => r.json())
                        .then(res => {
                            // Mettre à jour le panier dynamiquement (à compléter selon structure)
                            alert('Produit ajouté au panier !');
                            // TODO: rafraîchir le panier sans recharger la page
                        });
                    });
                });

                // Ajout dynamique de réparation
                const formReparation = document.getElementById('form-add-reparation');
                if(formReparation) {
                    formReparation.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const data = new FormData(formReparation);
                        fetch("<?php echo e(route('pos.storeRepair')); ?>", {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('input[name=_token]').value
                            },
                            body: data
                        })
                        .then(r => r.json())
                        .then(res => {
                            alert('Réparation ajoutée !');
                            // TODO: rafraîchir la liste des réparations sans recharger la page
                        });
                    });
                }
            });
            </script>
            <div class="card shadow-sm">
                <div class="card-body p-2">
                    <table class="table table-sm table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Description</th>
                                <th>État</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__empty_1 = true; $__currentLoopData = $reparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr>
                                <td><?php echo e($reparation->nom); ?></td>
                                <td><?php echo e($reparation->produit); ?></td>
                                <td><?php echo e(number_format($reparation->prix,2)); ?> DH</td>
                                <td><?php echo e($reparation->description); ?></td>
                                <td><span class="badge bg-<?php echo e($reparation->etat == 'en_cours' ? 'warning' : ($reparation->etat == 'terminee' ? 'success' : 'secondary')); ?>"><?php echo e(ucfirst(str_replace('_',' ',$reparation->etat))); ?></span></td>
                                <td><?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'N/A'); ?></td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr><td colspan="6" class="text-center text-muted">Aucune réparation</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        </div>
        <div class="alert alert-info text-center">Section ventes à personnaliser</div>
    </div>

    <?php if(request('details_id')): ?>
        <?php
            $details = $reparations->where('id', request('details_id'))->first();
        ?>
        <?php if($details): ?>
        <div class="modal fade show" tabindex="-1" style="display:block;background:rgba(0,0,0,0.3);" aria-modal="true" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title">Détails de la réparation</h5>
                        <a href="<?php echo e(route('pos.index', array_filter(['search_reparation'=>request('search_reparation')]))); ?>" class="btn-close" aria-label="Fermer"></a>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item"><strong>Client :</strong> <?php echo e($details->nom); ?></li>
                            <li class="list-group-item"><strong>Description :</strong> <?php echo e($details->description); ?></li>
                            <li class="list-group-item"><strong>Produit :</strong> <?php echo e($details->produit); ?></li>
                            <li class="list-group-item"><strong>Prix :</strong> <?php echo e(number_format($details->prix,2)); ?> DH</li>
                            <li class="list-group-item"><strong>Date :</strong> <?php echo e($details->date_reparation ? $details->date_reparation->format('d/m/Y H:i') : 'N/A'); ?></li>
                            <li class="list-group-item"><strong>État :</strong> <?php echo e(ucfirst(str_replace('_',' ',$details->etat))); ?></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <style>.modal-backdrop {display:none;}</style>
        <?php endif; ?>
    <?php endif; ?>
    <div id="reparations-section" style="display:none;">
        <h3 class="text-center mb-4">Réparations</h3>
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Réparations récentes</span>
                <input type="text" id="search-reparation" class="form-control form-control-sm w-50" placeholder="Rechercher réparation...">
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover mb-0" id="table-reparations">
                        <thead class="table-light">
                            <tr>
                                <th>Client</th>
                                <th>Description</th>
                                <th>Produit</th>
                                <th>Prix</th>
                                <th>Date</th>
                                <th>État</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $reparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr class="reparation-row" data-client="<?php echo e(strtolower($reparation->nom)); ?>" data-description="<?php echo e(strtolower($reparation->description)); ?>" data-produit="<?php echo e(strtolower($reparation->produit)); ?>">
                                    <td><?php echo e($reparation->nom); ?></td>
                                    <td><?php echo e($reparation->description); ?></td>
                                    <td><?php echo e($reparation->produit); ?></td>
                                    <td><?php echo e(number_format($reparation->prix, 2)); ?> DH</td>
                                    <td><?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'N/A'); ?></td>
                                    <td><span class="badge bg-<?php echo e($reparation->etat == 'en_cours' ? 'warning' : ($reparation->etat == 'terminee' ? 'success' : 'secondary')); ?>"><?php echo e(ucfirst(str_replace('_',' ',$reparation->etat))); ?></span></td>
                                        <td>-</td>
                                    </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="mb-3 text-end">
            <button type="button" class="btn btn-primary" id="btn-ajouter-reparation">Ajouter une réparation</button>
        </div>
        <!-- Modale ajout réparation -->
        <div class="modal fade" id="modal-add-reparation" tabindex="-1" aria-labelledby="modalAddReparationLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title" id="modalAddReparationLabel">Ajouter une réparation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <form method="POST" action="<?php echo e(route('pos.storeRepair')); ?>" id="form-add-reparation">
                        <?php echo csrf_field(); ?>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="nom-client" class="form-label">Client</label>
                                <input type="text" name="nom" id="nom-client" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label for="description-reparation" class="form-label">Description</label>
                                <textarea name="description" id="description-reparation" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="mb-3">
                                <label for="produit-reparation" class="form-label">Produit concerné</label>
                                <input type="text" name="produit" id="produit-reparation" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label for="prix-reparation" class="form-label">Prix</label>
                                <input type="number" name="prix" id="prix-reparation" class="form-control" min="0" step="0.01" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-success">Enregistrer</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- Modale détails réparation -->
        <div class="modal fade" id="modal-details-reparation" tabindex="-1" aria-labelledby="modalDetailsReparationLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-info text-white">
                        <h5 class="modal-title" id="modalDetailsReparationLabel">Détails de la réparation</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                    </div>
                    <div class="modal-body" id="details-reparation-body">
                        <!-- Contenu dynamique -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    let panier = {};
    let produits = <?php echo json_encode($produits, 15, 512) ?>;
    // Initialisation après chargement complet
    document.addEventListener('DOMContentLoaded', function() {
        // Navigation sections
        $('#btn-ventes').off('click').on('click', function() {
            $('#btn-ventes').addClass('btn-primary active').removeClass('btn-outline-primary');
            $('#btn-reparations').addClass('btn-outline-primary').removeClass('btn-primary active');
            $('#ventes-section').show();
            $('#reparations-section').hide();
        });
        $('#btn-reparations').off('click').on('click', function() {
            $('#btn-reparations').addClass('btn-primary active').removeClass('btn-outline-primary');
            $('#btn-ventes').addClass('btn-outline-primary').removeClass('btn-primary active');
            $('#ventes-section').hide();
            $('#reparations-section').show();
        });

        // Ajout produit au panier (modale de sélection)
        function openSelectProduit() {
            let html = '<div class="mb-3"><input type="text" id="modal-search" class="form-control" placeholder="Rechercher produit..."></div>';
            html += '<div style="max-height:300px;overflow-y:auto;">';
            html += '<ul class="list-group">';
            for (const prod of produits) {
                html += `<li class="list-group-item d-flex justify-content-between align-items-center produit-select-item" data-id="${prod.id}" data-nom="${prod.nom}" data-prix="${prod.prix_vente}" data-stock="${prod.quantite}" data-code="${prod.code}" data-description="${prod.description ?? ''}">
                    <span><strong>${prod.nom}</strong> <small>(${prod.code})</small></span>
                    <span class="badge bg-primary">${prod.prix_vente} DH</span>
                    <button type="button" class="btn btn-success btn-sm ms-2 select-produit">Ajouter</button>
                </li>`;
            }
            html += '</ul></div>';
            $('#details-body').html(html);
            $('#modal-details').modal('show');

            // Filtrage dans la modale
            $('#modal-search').off('input').on('input', function() {
                var search = $(this).val().toLowerCase();
                $('.produit-select-item').each(function() {
                    var nom = $(this).data('nom').toLowerCase();
                    var code = $(this).data('code').toLowerCase();
                    if (search === '' || nom.includes(search) || code.includes(search)) {
                        $(this).show();
                    } else {
                        $(this).hide();
                    }
                });
            });

            // Sélection produit
            $('.select-produit').off('click').on('click', function() {
                let li = $(this).closest('.produit-select-item');
                let id = li.data('id');
                let nom = li.data('nom');
                let prix = parseFloat(li.data('prix'));
                let stock = parseInt(li.data('stock'));
                if (!panier[id]) {
                    panier[id] = { nom: nom, prix: prix, quantite: 1, stock: stock, code: li.data('code'), description: li.data('description') };
                }
                updatePanier();
                $('#modal-details').modal('hide');
            });
        }

        // Bouton pour ouvrir la sélection produit
        $('#section-panier').prev('.mb-3.text-end').remove();
        $('#section-panier').before('<div class="mb-3 text-end"><button type="button" class="btn btn-primary" id="btn-ajouter-produit">Ajouter un produit au panier</button></div>');
        $(document).off('click', '#btn-ajouter-produit').on('click', '#btn-ajouter-produit', function() {
            openSelectProduit();
        });

        // Suppression du panier
        $(document).off('click', '.remove-panier').on('click', '.remove-panier', function() {
            let id = $(this).data('id');
            delete panier[id];
            updatePanier();
        });

        // Modification quantité panier
        $(document).off('change', '.input-quantite').on('change', '.input-quantite', function() {
            let id = $(this).data('id');
            let val = parseInt($(this).val());
            if (val > 0 && val <= panier[id].stock) {
                panier[id].quantite = val;
            } else {
                $(this).val(panier[id].quantite);
            }
            updatePanier();
        });

        // Modification prix panier
        $(document).off('change', '.input-prix').on('change', '.input-prix', function() {
            let id = $(this).data('id');
            let val = parseFloat($(this).val());
            if (val > 0) {
                panier[id].prix = val;
            } else {
                $(this).val(panier[id].prix);
            }
            updatePanier();
        });

        // Détails produit (modale)
        $(document).off('click', '.btn-details').on('click', '.btn-details', function() {
            let id = $(this).data('id');
            let item = panier[id];
            let html = `<ul class='list-group'>
                <li class='list-group-item'><strong>Nom :</strong> ${item.nom}</li>
                <li class='list-group-item'><strong>Code-barres :</strong> ${item.code}</li>
                <li class='list-group-item'><strong>Prix unitaire :</strong> ${item.prix} DH</li>
                <li class='list-group-item'><strong>Stock :</strong> ${item.stock}</li>
                <li class='list-group-item'><strong>Description :</strong> ${item.description ?? ''}</li>
            </ul>`;
            $('#details-body').html(html);
            $('#modal-details').modal('show');
        });

        // Recherche instantanée réparations
        $('#search-reparation').off('input').on('input', function() {
            var search = $(this).val().toLowerCase();
            $('#table-reparations .reparation-row').each(function() {
                var client = $(this).data('client');
                var desc = $(this).data('description');
                var prod = $(this).data('produit');
                if (search === '' || client.includes(search) || desc.includes(search) || prod.includes(search)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });

        // Bouton ajout réparation
        $(document).off('click', '#btn-ajouter-reparation').on('click', '#btn-ajouter-reparation', function() {
            $('#modal-add-reparation').modal('show');
        });

        // Bouton détails réparation
        $(document).off('click', '.btn-details-reparation').on('click', '.btn-details-reparation', function() {
            const tr = $(this).closest('tr');
            let html = `<ul class='list-group'>
                <li class='list-group-item'><strong>Client :</strong> ${tr.find('td').eq(0).text()}</li>
                <li class='list-group-item'><strong>Description :</strong> ${tr.find('td').eq(1).text()}</li>
                <li class='list-group-item'><strong>Produit :</strong> ${tr.find('td').eq(2).text()}</li>
                <li class='list-group-item'><strong>Prix :</strong> ${tr.find('td').eq(3).text()}</li>
                <li class='list-group-item'><strong>Date :</strong> ${tr.find('td').eq(4).text()}</li>
                <li class='list-group-item'><strong>État :</strong> ${tr.find('td').eq(5).text()}</li>
            </ul>`;
            $('#details-reparation-body').html(html);
            $('#modal-details-reparation').modal('show');
        });

        // Mise à jour du panier à l'initialisation
        updatePanier();

        function updatePanier() {
            let tbody = $('#table-panier tbody');
            tbody.empty();
            let total = 0;
            let count = 0;
            for (const id in panier) {
                const item = panier[id];
                let sousTotal = item.prix * item.quantite;
                total += sousTotal;
                count++;
                tbody.append(`
                    <tr>
                        <td>${item.nom} <button type="button" class="btn btn-info btn-sm btn-details ms-2" data-id="${id}">Détails</button></td>
                        <td><input type="number" class="form-control form-control-sm input-quantite" data-id="${id}" min="1" max="${item.stock}" value="${item.quantite}"></td>
                        <td><input type="number" class="form-control form-control-sm input-prix" data-id="${id}" min="0.01" step="0.01" value="${item.prix}"></td>
                        <td>${sousTotal.toFixed(2)} DH</td>
                        <td><button type="button" class="btn btn-danger btn-sm remove-panier" data-id="${id}"><i class="fas fa-trash"></i></button></td>
                    </tr>
                `);
            }
            $('#panier-total').text(total.toFixed(2) + ' DH');
            $('#btn-finaliser').prop('disabled', count === 0);
            // Remplir le champ hidden pour l'envoi
            $('#produits-json').val(JSON.stringify(Object.entries(panier).map(([id, item]) => ({id, quantite: item.quantite, prix_unitaire: item.prix}))));
        }
    });
</script>
<?php $__env->stopPush(); ?>





<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/pos/index.blade.php ENDPATH**/ ?>