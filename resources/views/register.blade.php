<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>La Clinique Du Smartphones - Inscription</title>
    <!-- Custom fonts for this template-->
    <link href="{{ asset('/back/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
        rel="stylesheet">
    <!-- Custom styles for this template-->
    <link href="{{ asset('/back/css/sb-admin-2.min.css') }}" rel="stylesheet">
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
                                <img src="{{ asset('img/logo complet.jpg') }}" 
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
                                    <form method="POST" action="{{ route('register.store') }}">
                                        @csrf
                                        <!-- Affichage des erreurs -->
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif

                                        <div class="form-group">
                                            <label for="nom">Nom</label>
                                            <input type="text" class="form-control form-control-Utilisateur" id="nom" name="nom" value="{{ old('nom') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="prenom">Prénom</label>
                                            <input type="text" class="form-control form-control-Utilisateur" id="prenom" name="prenom" value="{{ old('prenom') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="email">Adresse Email</label>
                                            <input type="email" class="form-control form-control-Utilisateur" id="email" name="email" value="{{ old('email') }}" required>
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
                                        {{-- <hr>
                                        <a href="{{ route('index') }}" class="btn btn-google btn-Utilisateur btn-block">
                                            <i class="fab fa-google fa-fw"></i> Login with Google
                                        </a>
                                        <a href="{{ route('index') }}" class="btn btn-facebook btn-Utilisateur btn-block">
                                            <i class="fab fa-facebook-f fa-fw"></i> Login with Facebook
                                        </a> --}}
                                    </form>
                                    <hr>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('login') }}">Déjà un compte? Connectez-vous!</a>
                                    </div>
                                    <div class="text-center">
                                        <a class="small" href="{{ route('home') }}">Retour à l'accueil</a>
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
    <script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Core plugin JavaScript-->
    <script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script src="{{ asset('js/sb-admin-2.min.js') }}"></script>
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/chart.js/Chart.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/chart-area-demo.js') }}"></script>
    <script src="{{ asset('js/demo/chart-pie-demo.js') }}"></script>
    <!-- Page level plugins -->
    <script src="{{ asset('vendor/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('vendor/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Page level custom scripts -->
    <script src="{{ asset('js/demo/datatables-demo.js') }}"></script>
</body>
</html>
