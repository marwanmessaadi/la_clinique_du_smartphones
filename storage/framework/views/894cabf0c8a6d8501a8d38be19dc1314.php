
<?php $__env->startSection('title', 'Point de Vente (Réparation)'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4" style="min-height:70vh;">

    <!-- Navigation POS -->
    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="<?php echo e(route('pos.vente')); ?>" class="btn btn-outline-primary">Vente Produits</a>
        <a href="<?php echo e(route('pos.reparation')); ?>" class="btn btn-primary active">Réparations</a>
    </div>

    <!-- Messages de succès/erreur -->
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- Ajouter réparation -->
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">Réparations</h5>
            <form method="GET" action="<?php echo e(route('pos.reparation')); ?>">
                <?php if(request('show_repair_form')): ?>
                    <button type="submit" class="btn btn-outline-secondary btn-sm">
                        <i class="bi bi-x-circle"></i> Annuler
                    </button>
                <?php else: ?>
                    <button type="submit" name="show_repair_form" value="1" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-wrench"></i> Ajouter réparation
                    </button>
                <?php endif; ?>
            </form>
        </div>

        <?php if(request('show_repair_form')): ?>
        <div class="card card-body mb-3 border-primary">
            <h6 class="mb-3">Nouvelle Réparation</h6>
            <form id="form-add-reparation" method="POST" action="<?php echo e(route('pos.storeRepair')); ?>">
                <?php echo csrf_field(); ?>
                <div class="row g-2 mb-2">
                    <div class="col-md-4">
                        <label class="form-label small">Client *</label>
                        <input type="text" name="nom" class="form-control" placeholder="Nom du client" value="<?php echo e(old('nom')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Produit *</label>
                        <input type="text" name="produit" class="form-control" placeholder="Produit à réparer" value="<?php echo e(old('produit')); ?>" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label small">Prix (DH) *</label>
                        <input type="number" name="prix" class="form-control" placeholder="Prix" step="0.01" min="0" value="<?php echo e(old('prix')); ?>" required>
                    </div>
                </div>
                <div class="row g-2 mb-2">
                    <div class="col-md-6">
                        <label class="form-label small">État *</label>
                        <select name="etat" class="form-select" required>
                            <option value="en_cours" <?php echo e(old('etat') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                            <option value="terminee" <?php echo e(old('etat') == 'terminee' ? 'selected' : ''); ?>>Terminée</option>
                            <option value="annulee" <?php echo e(old('etat') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label small">Client (optionnel)</label>
                        <select name="client_id" class="form-select">
                            <option value="">Sélectionner un client</option>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->id); ?>" <?php echo e(old('client_id') == $client->id ? 'selected' : ''); ?>>
                                    <?php echo e($client->prenom); ?> <?php echo e($client->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Description</label>
                    <textarea name="description" class="form-control" placeholder="Description de la réparation..." rows="2"><?php echo e(old('description')); ?></textarea>
                </div>
                <div class="mb-2">
                    <label class="form-label small">Notes (optionnel)</label>
                    <textarea name="notes" class="form-control" placeholder="Notes supplémentaires..." rows="1"><?php echo e(old('notes')); ?></textarea>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer
                    </button>
                </div>
            </form>
        </div>
        <?php endif; ?>

        <!-- Formulaire de recherche -->
        <div class="card shadow-sm mb-3">
            <div class="card-body">
                <form method="GET" action="<?php echo e(route('pos.reparation')); ?>" class="row g-2">
                    <div class="col-md-8">
                        <div class="input-group">
                            <input type="text" name="search_reparation" class="form-control" 
                                   placeholder="Rechercher par client, produit, code..." 
                                   value="<?php echo e(request('search_reparation')); ?>">
                            <button class="btn btn-outline-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <select name="etat_filter" class="form-select" onchange="this.form.submit()">
                            <option value="">Tous les états</option>
                            <option value="en_cours" <?php echo e(request('etat_filter') == 'en_cours' ? 'selected' : ''); ?>>En cours</option>
                            <option value="terminee" <?php echo e(request('etat_filter') == 'terminee' ? 'selected' : ''); ?>>Terminée</option>
                            <option value="annulee" <?php echo e(request('etat_filter') == 'annulee' ? 'selected' : ''); ?>>Annulée</option>
                        </select>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tableau des réparations -->
        <div class="card shadow-sm">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Code</th>
                                <th>Client</th>
                                <th>Produit</th>
                                <th>Prix (DH)</th>
                                <th>Description</th>
                                <th>État</th>
                                <th>Date</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="reparations-list">
                            <?php $__empty_1 = true; $__currentLoopData = $reparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <tr class="reparation-row" 
                                data-client="<?php echo e(strtolower($reparation->nom)); ?>" 
                                data-produit="<?php echo e(strtolower($reparation->produit)); ?>" 
                                data-description="<?php echo e(strtolower($reparation->description ?? '')); ?>"
                                data-etat="<?php echo e($reparation->etat); ?>"
                                data-code="<?php echo e(strtolower($reparation->code ?? '')); ?>">
                                <td>
                                    <span class="badge bg-secondary text-white"><?php echo e($reparation->code ?? 'N/A'); ?></span>
                                </td>
                                <td><?php echo e($reparation->nom); ?></td>
                                <td><?php echo e($reparation->produit); ?></td>
                                <td>
                                    <strong><?php echo e(number_format($reparation->prix, 2)); ?> DH</strong>
                                </td>
                                <td>
                                    <small class="text-muted">
                                        <?php echo e(Str::limit($reparation->description ?? '', 50)); ?>

                                    </small>
                                </td>
                                <td>
                                    <span class="text-white badge bg-<?php echo e($reparation->etat == 'en_cours' ? 'warning' : ($reparation->etat == 'terminee' ? 'success' : ($reparation->etat == 'annulee' ? 'danger' : 'secondary'))); ?>">
                                        <?php echo e(ucfirst(str_replace('_',' ',$reparation->etat))); ?>

                                    </span>
                                </td>
                                <td>
                                    <small>
                                        <?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'N/A'); ?>

                                    </small>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group btn-group-sm" role="group">
                                        

                                        <!-- Ticket -->
                                        <a href="<?php echo e(route('pos.repairTicket', $reparation->id)); ?>" 
                                           target="_blank" 
                                           class="btn btn-outline-success" 
                                           title="Imprimer ticket">
                                            <i class="bi bi-printer"></i>
                                        </a>

                                        
                                        
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="text-muted">
                                        <i class="bi bi-wrench display-6 d-block mb-2"></i>
                                        <?php if(request('search_reparation') || request('etat_filter')): ?>
                                            Aucune réparation trouvée avec ces critères
                                        <?php else: ?>
                                            Aucune réparation enregistrée
                                        <?php endif; ?>
                                    </div>
                                    <?php if(!request('show_repair_form')): ?>
                                    <a href="<?php echo e(route('pos.reparation')); ?>?show_repair_form=1" class="btn btn-sm btn-primary mt-2">
                                        <i class="bi bi-plus-circle"></i> Ajouter une réparation
                                    </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <?php if($reparations instanceof \Illuminate\Pagination\LengthAwarePaginator && $reparations->total() > 0): ?>
                <div class="card-footer">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            Affichage de <?php echo e($reparations->firstItem()); ?> à <?php echo e($reparations->lastItem()); ?> sur <?php echo e($reparations->total()); ?> réparations
                        </div>
                        <div>
                            <?php echo e($reparations->withQueryString()->links()); ?>

                        </div>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Modal édition réparation -->
<div class="modal fade" id="modalEditReparation" tabindex="-1" aria-labelledby="modalEditReparationLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="form-edit-reparation" method="POST" action="<?php echo e(route('pos.storeRepair')); ?>">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="id" id="edit-id">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalEditReparationLabel">Modifier la réparation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Client *</label>
                        <input type="text" name="nom" id="edit-nom" class="form-control" placeholder="Nom du client" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Produit *</label>
                        <input type="text" name="produit" id="edit-produit" class="form-control" placeholder="Produit à réparer" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Prix (DH) *</label>
                        <input type="number" name="prix" id="edit-prix" class="form-control" placeholder="Prix" step="0.01" min="0" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">État *</label>
                        <select name="etat" id="edit-etat" class="form-select" required>
                            <option value="en_cours">En cours</option>
                            <option value="terminee">Terminée</option>
                            <option value="annulee">Annulée</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Client (optionnel)</label>
                        <select name="client_id" id="edit-client_id" class="form-select">
                            <option value="">Sélectionner un client</option>
                            <?php $__currentLoopData = $clients; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $client): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($client->id); ?>">
                                    <?php echo e($client->prenom); ?> <?php echo e($client->nom); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" id="edit-description" class="form-control" placeholder="Description de la réparation..." rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" id="edit-notes" class="form-control" placeholder="Notes supplémentaires..." rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Enregistrer les modifications
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal confirmation suppression -->
<div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirmation de suppression</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Êtes-vous sûr de vouloir supprimer cette réparation ? Cette action est irréversible.</p>
            </div>
            <div class="modal-footer">
                <form id="deleteForm" method="POST" action="">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash"></i> Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
    .reparation-row:hover {
        background-color: #f8f9fa;
        cursor: pointer;
    }
    
    .badge {
        font-size: 0.75em;
    }
    
    .table td, .table th {
        vertical-align: middle;
    }
    
    .btn-group-sm > .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
    
    .card-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    console.log('Script chargé'); // Debug

    // Édition réparation
    const editButtons = document.querySelectorAll('.btn-edit-reparation');
    console.log('Boutons trouvés:', editButtons.length); // Debug
    
    editButtons.forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            
            console.log('Bouton cliqué', this.dataset); // Debug
            
            // Remplir les champs du modal
            document.getElementById('edit-id').value = this.dataset.id || '';
            document.getElementById('edit-nom').value = this.dataset.nom || '';
            document.getElementById('edit-produit').value = this.dataset.produit || '';
            document.getElementById('edit-prix').value = this.dataset.prix || '';
            document.getElementById('edit-description').value = this.dataset.description || '';
            document.getElementById('edit-etat').value = this.dataset.etat || 'en_cours';
            document.getElementById('edit-client_id').value = this.dataset.client_id || '';
            document.getElementById('edit-notes').value = this.dataset.notes || '';
            
            // Ouvrir le modal
            const modalElement = document.getElementById('modalEditReparation');
            const modal = new bootstrap.Modal(modalElement);
            modal.show();
        });
    });

    // Confirmation suppression
    window.confirmDelete = function(id) {
        const form = document.getElementById('deleteForm');
        form.action = '<?php echo e(route("pos.deleteRepair", ":id")); ?>'.replace(':id', id);
        
        const modal = new bootstrap.Modal(document.getElementById('deleteConfirmationModal'));
        modal.show();
    };

    // Validation du formulaire
    const addForm = document.getElementById('form-add-reparation');
    const editForm = document.getElementById('form-edit-reparation');
    
    [addForm, editForm].forEach(form => {
        if (form) {
            form.addEventListener('submit', function(e) {
                const prix = this.querySelector('input[name="prix"]');
                if (prix && parseFloat(prix.value) < 0) {
                    e.preventDefault();
                    alert('Le prix ne peut pas être négatif');
                    prix.focus();
                    return false;
                }
            });
        }
    });

    // Formatage automatique du prix
    document.querySelectorAll('input[name="prix"]').forEach(input => {
        input.addEventListener('blur', function() {
            if (this.value && this.value !== '') {
                this.value = parseFloat(this.value).toFixed(2);
            }
        });
    });

    // Sélection rapide d'une ligne - DÉSACTIVÉ pour éviter les conflits
    // document.querySelectorAll('.reparation-row').forEach(row => {
    //     row.addEventListener('click', function(e) {
    //         if (!e.target.closest('button') && !e.target.closest('a')) {
    //             const btnEdit = this.querySelector('.btn-edit-reparation');
    //             if (btnEdit) btnEdit.click();
    //         }
    //     });
    // });

    // Auto-focus sur le champ de recherche
    const searchInput = document.querySelector('input[name="search_reparation"]');
    if (searchInput && !document.querySelector('.modal.show')) {
        searchInput.focus();
    }
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/pos/reparation.blade.php ENDPATH**/ ?>