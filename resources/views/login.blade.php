<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="La Clinique du Smartphone - Connexion">
    <meta name="author" content="La Clinique du Smartphone">

    <title>La Clinique du Smartphone - Connexion</title>

    <!-- Custom fonts -->
    <link href="vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles -->
    <link href="/back/css/sb-admin-2.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%) !important;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
        }

        .login-container {
            margin: 2rem auto;
            max-width: 1000px;
            width: 100%;
            padding: 0 1rem;
        }

        .login-card {
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            background: white;
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .image-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .image-section img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 10px;
        }

        .form-section {
            padding: 3rem 2.5rem;
            background: white;
        }

        .welcome-text {
            text-align: center;
            margin-bottom: 2rem;
        }

        .welcome-text h1 {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }

        .welcome-text p {
            color: #6c757d;
            font-size: 0.95rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            font-weight: 600;
            color: #2d3748;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-control {
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            transition: all 0.3s ease;
        }

        .form-control:focus {
            border-color: #764ba2;
            box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.1);
            outline: none;
        }

        .input-icon {
            position: relative;
        }

        .input-icon i {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }

        .input-icon .form-control {
            padding-left: 2.75rem;
        }

        .btn-login {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            border: none;
            border-radius: 10px;
            padding: 0.85rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            color: white;
            width: 100%;
            transition: all 0.3s ease;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(118, 75, 162, 0.3);
            background: linear-gradient(135deg, #5568d3 0%, #6a3f91 50%, #e082ea 100%);
        }

        .custom-checkbox .custom-control-label {
            color: #4a5568;
            font-size: 0.9rem;
        }

        .custom-control-input:checked ~ .custom-control-label::before {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-color: #764ba2;
        }

        .alert {
            border-radius: 10px;
            border: none;
            padding: 1rem;
            margin-bottom: 1.5rem;
        }

        .alert-danger {
            background: linear-gradient(135deg, #fff5f5 0%, #fed7d7 100%);
            color: #c53030;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.25rem;
        }

        .divider {
            margin: 1.5rem 0;
            border-top: 2px solid #e2e8f0;
        }

        /* Responsive */
        @media (max-width: 991px) {
            .image-section {
                min-height: 250px;
            }

            .form-section {
                padding: 2rem 1.5rem;
            }
        }

        @media (max-width: 576px) {
            .welcome-text h1 {
                font-size: 1.5rem;
            }

            .form-section {
                padding: 1.5rem 1rem;
            }
        }

        /* Animation pour les champs */
        .form-control {
            animation: fadeIn 0.6s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateX(-10px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        /* Logo styling */
        .brand-logo {
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .brand-logo img {
            max-width: 80px;
            height: auto;
        }
    </style>
</head>

<body>

    <div class="login-container">
        <div class="card login-card border-0">
            <div class="card-body p-0">
                <div class="row g-0">
                    
                    <!-- Section Image -->
                    <div class="col-lg-6 d-none d-lg-block image-section">
                        <img src="{{ asset('img/logo login.png') }}" 
                             alt="La Clinique du Smartphone" 
                             class="img-fluid">
                    </div>

                    <!-- Section Formulaire -->
                    <div class="col-lg-6">
                        <div class="form-section">
                            
                            <!-- Logo Mobile -->
                            <div class="brand-logo d-lg-none">
                                <img src="{{ asset('img/LOGO.png') }}" alt="Logo">
                            </div>

                            <!-- Titre -->
                            <div class="welcome-text">
                                <h1>Bienvenue</h1>
                                <p>Connectez-vous à votre espace</p>
                            </div>

                            <!-- Formulaire -->
                            <form method="POST" action="{{ route('login_valid') }}">
                                @csrf

                                <!-- Messages d'erreur -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <strong><i class="fas fa-exclamation-circle"></i> Erreur :</strong>
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                @if (session('error'))
                                    <div class="alert alert-danger">
                                        <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
                                    </div>
                                @endif

                                <!-- Email -->
                                <div class="form-group">
                                    <label for="exampleInputEmail">
                                        <i class="fas fa-envelope"></i> Adresse Email
                                    </label>
                                    <div class="input-icon">
                                        <i class="fas fa-user"></i>
                                        <input type="email" 
                                               class="form-control" 
                                               name="email" 
                                               id="exampleInputEmail" 
                                               placeholder="votreemail@exemple.com" 
                                               required 
                                               autocomplete="email"
                                               value="{{ old('email') }}">
                                    </div>
                                </div>

                                <!-- Mot de passe -->
                                <div class="form-group">
                                    <label for="exampleInputPassword">
                                        <i class="fas fa-lock"></i> Mot de Passe
                                    </label>
                                    <div class="input-icon">
                                        <i class="fas fa-key"></i>
                                        <input type="password" 
                                               class="form-control" 
                                               name="password" 
                                               id="exampleInputPassword" 
                                               placeholder="••••••••" 
                                               required 
                                               autocomplete="current-password">
                                    </div>
                                </div>

                                <!-- Remember Me -->
                                <div class="form-group">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" 
                                               class="custom-control-input" 
                                               id="customCheck"
                                               name="remember">
                                        <label class="custom-control-label" for="customCheck">
                                            Se souvenir de moi
                                        </label>
                                    </div>
                                </div>

                                <!-- Bouton de connexion -->
                                <button type="submit" class="btn btn-login">
                                    <i class="fas fa-sign-in-alt mr-2"></i> Se Connecter
                                </button>
                            </form>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="js/sb-admin-2.min.js"></script>

</body>

</html>