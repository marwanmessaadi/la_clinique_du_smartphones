<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>La Clinique Du Smartphones - Inscription</title>
    <!-- Custom fonts for this template-->
    <link href="<?php echo e(asset('/back/vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="<?php echo e(asset('/back/css/sb-admin-2.min.css')); ?>" rel="stylesheet">
</head>
<body class="bg-gradient-dark">
    <div class="container">
        <!-- Outer Row -->
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <!-- Nested Row within Card Body -->
                        <div class="row">
                            <!-- Colonne gauche avec l'image -->
                            <div class="col-lg-6 d-none d-lg-block">
                                <img src="<?php echo e(asset('img/LOGO.png')); ?>" 
                                     alt="Logo La Clinique du Smartphone" 
                                     class="img-fluid" 
                                     style="height: 100%; width: 100%; object-fit: cover;">
                            </div>

                            <!-- Colonne droite avec le formulaire -->
                            <div class="col-lg-6">
                                <div class="p-5">
                                    <div class="text-center">
                                        <h1 class="h4 text-gray-900 mb-4">Créer un Compte!</h1>
                                    </div>
                                    <form method="POST" action="<?php echo e(route('register.store')); ?>">
                                        <?php echo csrf_field(); ?>
                                        <!-- Affichage des erreurs -->
                                        <?php if($errors->any()): ?>
                                            <div class="alert alert-danger">
                                                <ul>
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($error); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>

                                        <div class="form-group">
                                            <label for="nom">Nom</label>
                                            <input type="text" class="form-control form-control-Utilisateur" id="nom" name="nom" value="<?php echo e(old('nom')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="prenom">Prénom</label>
                                            <input type="text" class="form-control form-control-Utilisateur" id="prenom" name="prenom" value="<?php echo e(old('prenom')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Adresse Email</label>
                                            <input type="email" class="form-control form-control-Utilisateur" id="email" name="email" value="<?php echo e(old('email')); ?>" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password">Mot de Passe</label>
                                            <input type="password" class="form-control form-control-Utilisateur" id="password" name="password" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="password_confirmation">Confirmer le Mot de Passe</label>
                                            <input type="password" class="form-control form-control-Utilisateur" id="password_confirmation" name="password_confirmation" required>
                                        </div>
                                         <div class="form-group">
                                            <label  for="role">Rôle</label>
                                            <select class="form-control" id="role" name="role" required>
                                                <option value="client">Client</option>
                                            </select>
                                        </div> 
                                        <button type="submit" class="btn btn-primary btn-Utilisateur btn-block">Créer un Compte</button>
                                        
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="<?php echo e(route('login')); ?>">Déjà un compte? Connectez-vous!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="<?php echo e(route('home')); ?>">Retour à l'accueil</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>      
            </div>
        </div>
    </div>
    <!-- Bootstrap core JavaScript-->
    <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?php echo e(asset('vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?php echo e(asset('js/sb-admin-2.min.js')); ?>"></script>
    <!-- Page level plugins -->
    <script src="<?php echo e(asset('vendor/chart.js/Chart.min.js')); ?>"></script>
    <!-- Page level custom scripts -->
    <script src="<?php echo e(asset('js/demo/chart-area-demo.js')); ?>"></script>
    <script src="<?php echo e(asset('js/demo/chart-pie-demo.js')); ?>"></script>
    <!-- Page level plugins -->
    <script src="<?php echo e(asset('vendor/datatables/jquery.dataTables.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/datatables/dataTables.bootstrap4.min.js')); ?>"></script>
    <!-- Page level custom scripts -->
    <script src="<?php echo e(asset('js/demo/datatables-demo.js')); ?>"></script>
</body>
</html>
<?php /**PATH C:\Users\marwan\clinique\resources\views/register.blade.php ENDPATH**/ ?>