
<?php $__env->startSection('title', 'Ajouter un Produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-12">
            <div class="form-card" style="max-width: 98vw; margin: 0 auto;">
                <div class="form-header">
                    <h1 class="form-title">Ajouter un Nouveau Produit</h1>
                    <p class="form-subtitle">Créez un nouveau produit dans votre inventaire</p>
                </div>

                <div class="form-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php if($errors->has('multi')): ?>
                                    <?php $__currentLoopData = $errors->get('multi'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $row => $rowErrors): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php $__currentLoopData = $rowErrors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $err): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li>Ligne <?php echo e($row+1); ?> : <?php echo e($err); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php else: ?>
                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li><?php echo e($error); ?></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endif; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('produits.store')); ?>" method="POST" enctype="multipart/form-data" id="multiProductForm">
                        <?php echo csrf_field(); ?>
                        <div class="table-responsive" style="padding: 0 2vw;">
                            <table class="table table-bordered table-striped align-middle w-100" id="productsTable" style="background:#fff; font-size:1.15rem;">
                                <thead class="table-dark">
                                    <tr class="text-center align-middle" style="font-size:1.2rem;">
                                        <th style="min-width:120px">Nom <span class="text-danger">*</span></th>
                                        <th style="min-width:140px">Description</th>
                                        <th style="min-width:120px">Catégorie <span class="text-danger">*</span></th>
                                        <th style="min-width:120px">Fournisseur</th>
                                        <th style="min-width:100px">Prix d'achat <span class="text-danger">*</span></th>
                                        <th style="min-width:100px">Prix de vente <span class="text-danger">*</span></th>
                                        <th style="min-width:100px">Prix de gros</th>
                                        <th style="min-width:80px">Quantité</th>
                                        <th style="min-width:140px">Date d'achat</th>
                                        <th style="min-width:120px">Image</th>
                                        <th style="min-width:60px">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td><input type="text" name="produits[0][nom]" class="form-control" required placeholder="Nom du produit" style="font-size:1.15rem;"></td>
                                        <td><textarea name="produits[0][description]" class="form-control" rows="2" placeholder="Description" style="font-size:1.15rem;"></textarea></td>
                                        <td>
                                            <select name="produits[0][categorie_id]" class="form-select" required style="font-size:1.15rem;">
                                                <option value="">Catégorie...</option>
                                                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($categorie->id); ?>"><?php echo e($categorie->nom); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td>
                                            <select name="produits[0][fournisseur_id]" class="form-select" style="font-size:1.15rem;">
                                                <option value="">Fournisseur...</option>
                                                <?php $__currentLoopData = \App\Models\Fournisseur::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($fournisseur->id); ?>"><?php echo e($fournisseur->nom); ?></option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </td>
                                        <td><input type="number" name="produits[0][prix_achat]" class="form-control" step="0.01" min="0" required placeholder="0.00" style="font-size:1.15rem;"></td>
                                        <td><input type="number" name="produits[0][prix_vente]" class="form-control" step="0.01" min="0" required placeholder="0.00" style="font-size:1.15rem;"></td>
                                        <td><input type="number" name="produits[0][prix_gros]" class="form-control" step="0.01" min="0" placeholder="0.00" style="font-size:1.15rem;"></td>
                                        <td><input type="number" name="produits[0][quantite]" class="form-control" min="0" placeholder="0" style="font-size:1.15rem;"></td>
                                        <td><input type="datetime-local" name="produits[0][date_achat]" class="form-control" value="<?php echo e(now()->format('Y-m-d\TH:i')); ?>" style="font-size:1.15rem;"></td>
                                        <td><input type="file" name="produits[0][image]" class="form-control" accept="image/*" style="font-size:1.15rem;"></td>
                                        <td class="text-center"><button type="button" class="btn btn-outline-danger btn-lg remove-row" title="Supprimer" style="font-size:1.2rem;"><i class="fas fa-trash"></i></button></td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="d-flex justify-content-end mb-3">
                                <button type="button" class="btn btn-outline-success btn-xl px-5 py-2" id="addRowBtn" style="font-size:1.25rem;"><i class="fas fa-plus"></i> Ajouter un produit</button>
                            </div>
                        </div>
                        <div class="text-center mt-5 mb-3">
                            <button type="submit" class="btn btn-primary btn-xl px-5 py-2" style="font-size:1.3rem;">
                                <i class="fas fa-save"></i> Enregistrer les produits
                            </button>
                            <a href="<?php echo e(route('produits.index')); ?>" class="btn btn-secondary btn-xl ms-3 px-5 py-2" style="font-size:1.3rem;">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    $(document).ready(function() {
        let rowIdx = 1;
        $('#addRowBtn').click(function() {
            let newRow = $('#productsTable tbody tr:first').clone();
            newRow.find('input, select, textarea').each(function() {
                let name = $(this).attr('name');
                if (name) {
                    let newName = name.replace(/produits\[0\]/, 'produits[' + rowIdx + ']');
                    $(this).attr('name', newName);
                    if ($(this).is('input[type="file"]')) {
                        $(this).val('');
                    } else {
                        $(this).val('');
                    }
                }
            });
            newRow.appendTo('#productsTable tbody');
            rowIdx++;
        });

        // Remove row
        $('#productsTable').on('click', '.remove-row', function() {
            if ($('#productsTable tbody tr').length > 1) {
                $(this).closest('tr').remove();
            }
        });

        // Validation des prix pour chaque ligne
        $('#multiProductForm').on('submit', function(e) {
            let valid = true;
            let errorMsg = '';
            $('#productsTable tbody tr').each(function(idx) {
                // Champs requis
                let nom = $(this).find('input[name*="[nom]"]');
                let prixAchat = $(this).find('input[name*="[prix_achat]"]');
                let prixVente = $(this).find('input[name*="[prix_vente]"]');
                let categorie = $(this).find('select[name*="[categorie_id]"]');
                // Reset styles
                nom.removeClass('is-invalid');
                prixAchat.removeClass('is-invalid');
                prixVente.removeClass('is-invalid');
                categorie.removeClass('is-invalid');
                // Validation
                if (!nom.val()) {
                    nom.addClass('is-invalid');
                    valid = false;
                    errorMsg = 'Le champ Nom est requis dans la ligne ' + (idx+1);
                }
                if (!prixAchat.val()) {
                    prixAchat.addClass('is-invalid');
                    valid = false;
                    errorMsg = 'Le champ Prix achat est requis dans la ligne ' + (idx+1);
                }
                if (!prixVente.val()) {
                    prixVente.addClass('is-invalid');
                    valid = false;
                    errorMsg = 'Le champ Prix vente est requis dans la ligne ' + (idx+1);
                }
                if (!categorie.val()) {
                    categorie.addClass('is-invalid');
                    valid = false;
                    errorMsg = 'Le champ Catégorie est requis dans la ligne ' + (idx+1);
                }
                // Validation prix
                const prixAchatVal = parseFloat(prixAchat.val()) || 0;
                const prixVenteVal = parseFloat(prixVente.val()) || 0;
                const prixGrosVal = parseFloat($(this).find('input[name*="[prix_gros]"]').val());
                if (prixVenteVal <= prixAchatVal) {
                    prixVente.addClass('is-invalid');
                    valid = false;
                    errorMsg = 'Le prix de vente doit être supérieur au prix d\'achat (ligne ' + (idx+1) + ')';
                    return false;
                }
                if (!isNaN(prixGrosVal) && prixGrosVal > 0) {
                    if (prixGrosVal <= prixAchatVal || prixGrosVal >= prixVenteVal) {
                        $(this).find('input[name*="[prix_gros]"]').addClass('is-invalid');
                        valid = false;
                        errorMsg = 'Le prix de gros doit être compris entre le prix d\'achat et le prix de vente (ligne ' + (idx+1) + ')';
                        return false;
                    }
                }
            });
            if (!valid) {
                e.preventDefault();
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de saisie',
                    text: errorMsg
                });
                return false;
            }
        });
    <style>
        .is-invalid {
            background-color: #ffe5e5 !important;
            border-color: #dc3545 !important;
        }
    </style>
    });
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/create.blade.php ENDPATH**/ ?>