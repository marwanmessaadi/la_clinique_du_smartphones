
<?php $__env->startSection('title', 'Modifier un Produit'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="form-card">
                <div class="form-header">
                    <h1 class="form-title">Modifier le Produit</h1>
                    <p class="form-subtitle">Mettez à jour les informations du produit</p>
                </div>

                <div class="form-body">
                    <?php if($errors->any()): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul class="mb-0">
                                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($error); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form action="<?php echo e(route('produits.update', $produit->id)); ?>" method="POST" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PUT'); ?>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="nom">Nom du Produit *</label>
                                    <input type="text" class="form-control" id="nom" name="nom" value="<?php echo e($produit->nom); ?>" required>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" rows="3"><?php echo e($produit->description); ?></textarea>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="categorie_id">Catégorie</label>
                                    <select class="form-control" id="categorie_id" name="categorie_id">
                                        <option value="">Sélectionner une catégorie</option>
                                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $categorie): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($categorie->id); ?>" <?php echo e($produit->categorie_id == $categorie->id ? 'selected' : ''); ?>>
                                                <?php echo e($categorie->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="fournisseur_id">Fournisseur</label>
                                    <select class="form-control" id="fournisseur_id" name="fournisseur_id">
                                        <option value="">Sélectionner un fournisseur</option>
                                        <?php $__currentLoopData = \App\Models\Fournisseur::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $fournisseur): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <option value="<?php echo e($fournisseur->id); ?>" <?php echo e($produit->fournisseur_id == $fournisseur->id ? 'selected' : ''); ?>>
                                                <?php echo e($fournisseur->nom); ?>

                                            </option>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="form-label" for="prix_achat">Prix d'achat *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_achat" name="prix_achat" value="<?php echo e($produit->prix_achat); ?>" step="0.01" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prix_vente">Prix de vente *</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_vente" name="prix_vente" value="<?php echo e($produit->prix_vente); ?>" step="0.01" required>
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="prix_gros">Prix de gros</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" id="prix_gros" name="prix_gros" value="<?php echo e($produit->prix_gros); ?>" step="0.01">
                                        <span class="input-group-text">€</span>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="quantite">Quantité</label>
                                    <input type="number" class="form-control" id="quantite" name="quantite" value="<?php echo e($produit->quantite); ?>" min="0">
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="type">Type</label>
                                    <select class="form-control" id="type" name="type">
                                        <option value="achat" <?php echo e($produit->type == 'achat' ? 'selected' : ''); ?>>Achat</option>
                                        <option value="vente" <?php echo e($produit->type == 'vente' ? 'selected' : ''); ?>>Vente</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label class="form-label" for="image">Image du produit</label>
                                    <input type="file" class="form-control" id="image" name="image" accept="image/*">
                                    <?php if($produit->image): ?>
                                        <div class="current-image">
                                            <img src="<?php echo e(Str::startsWith($produit->image, 'http') ? $produit->image : asset('storage/' . $produit->image)); ?>" alt="<?php echo e($produit->nom); ?>" class="image-preview">
                                            <small class="text-muted d-block mt-1">Image actuelle</small>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <div class="text-center mt-4">
                            <button type="submit" class="btn btn-submit">
                                <i class="fas fa-save"></i> Mettre à jour le produit
                            </button>
                            <a href="<?php echo e(route('produits.show', $produit->id)); ?>" class="btn btn-cancel">
                                <i class="fas fa-times"></i> Annuler
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/produits/edit.blade.php ENDPATH**/ ?>