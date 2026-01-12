
<?php $__env->startSection('title', 'Point de Vente (Réparation)'); ?>
<?php $__env->startSection('content'); ?>
<div class="container py-4" style="min-height:70vh;">
    <div class="d-flex justify-content-center mb-4 gap-3">
        <a href="<?php echo e(route('pos.vente')); ?>" class="btn btn-outline-primary">Vente Produits</a>
        <a href="<?php echo e(route('pos.reparation')); ?>" class="btn btn-primary active">Réparations</a>
    </div>
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h5 class="mb-0">Réparations</h5>
            <form method="GET" action="<?php echo e(route('pos.reparation')); ?>">
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
        <div class="card shadow-sm">
            <div class="card-body p-2">
                <div class="mb-3">
                    <input type="text" id="search-reparation" class="form-control" placeholder="Rechercher réparation par client, produit, description...">
                </div>
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Client</th>
                            <th>Produit</th>
                            <th>Prix</th>
                            <th>Description</th>
                            <th>État</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="reparations-list">
                        <?php $__empty_1 = true; $__currentLoopData = $reparations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $reparation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr class="reparation-row" data-client="<?php echo e(strtolower($reparation->nom)); ?>" data-produit="<?php echo e(strtolower($reparation->produit)); ?>" data-description="<?php echo e(strtolower($reparation->description)); ?>">
                            <td><?php echo e($reparation->nom); ?></td>
                            <td><?php echo e($reparation->produit); ?></td>
                            <td><?php echo e(number_format($reparation->prix,2)); ?> DH</td>
                            <td><?php echo e($reparation->description); ?></td>
                            <td><span class="badge bg-<?php echo e($reparation->etat == 'en_cours' ? 'warning' : ($reparation->etat == 'terminee' ? 'success' : 'secondary')); ?>"><?php echo e(ucfirst(str_replace('_',' ',$reparation->etat))); ?></span></td>
                            <td><?php echo e($reparation->date_reparation ? $reparation->date_reparation->format('d/m/Y H:i') : 'N/A'); ?></td>
                            <td>
                                <button class="btn btn-sm btn-info btn-edit-reparation" 
                                    data-id="<?php echo e($reparation->id); ?>"
                                    data-nom="<?php echo e($reparation->nom); ?>"
                                    data-produit="<?php echo e($reparation->produit); ?>"
                                    data-prix="<?php echo e($reparation->prix); ?>"
                                    data-description="<?php echo e($reparation->description); ?>"
                                    data-etat="<?php echo e($reparation->etat); ?>"
                                >
                                    <i class="bi bi-pencil-square"></i> Modifier
                                </button>
                                <form method="POST" action="<?php echo e(route('pos.deleteRepair', $reparation->id)); ?>" style="display:inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Supprimer cette réparation ?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr><td colspan="7" class="text-center text-muted">Aucune réparation</td></tr>
                        <?php endif; ?>
                    </tbody>
                </table>
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
                    <div class="mb-2">
                        <input type="text" name="nom" id="edit-nom" class="form-control" placeholder="Client" required>
                    </div>
                    <div class="mb-2">
                        <input type="text" name="produit" id="edit-produit" class="form-control" placeholder="Produit à réparer" required>
                    </div>
                    <div class="mb-2">
                        <input type="number" name="prix" id="edit-prix" class="form-control" placeholder="Prix" step="0.01" required>
                    </div>
                    <div class="mb-2">
                        <textarea name="description" id="edit-description" class="form-control" placeholder="Description"></textarea>
                    </div>
                    <div class="mb-2">
                        <select name="etat" id="edit-etat" class="form-select">
                            <option value="en_cours">En cours</option>
                            <option value="terminee">Terminée</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-primary">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Edition
    document.querySelectorAll('.btn-edit-reparation').forEach(function(btn) {
        btn.addEventListener('click', function() {
            document.getElementById('edit-id').value = this.dataset.id;
            document.getElementById('edit-nom').value = this.dataset.nom;
            document.getElementById('edit-produit').value = this.dataset.produit;
            document.getElementById('edit-prix').value = this.dataset.prix;
            document.getElementById('edit-description').value = this.dataset.description;
            document.getElementById('edit-etat').value = this.dataset.etat;
            var modal = new bootstrap.Modal(document.getElementById('modalEditReparation'));
            modal.show();
        });
    });
    // Recherche dynamique
    document.getElementById('search-reparation').addEventListener('input', function() {
        const val = this.value.trim().toLowerCase();
        document.querySelectorAll('#reparations-list .reparation-row').forEach(function(row) {
            const client = row.dataset.client;
            const produit = row.dataset.produit;
            const description = row.dataset.description;
            if(val === '' || client.includes(val) || produit.includes(val) || description.includes(val)) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
    });
});
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/pos/reparation.blade.php ENDPATH**/ ?>