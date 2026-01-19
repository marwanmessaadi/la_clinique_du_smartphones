<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>404 - Page Non Trouvée | La Clinique du Smartphone</title>

    <!-- Fonts -->
    <link href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Styles -->
    <link href="<?php echo e(asset('/back/css/sb-admin-2.min.css')); ?>" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Nunito', sans-serif;
        }

        .error-container {
            text-align: center;
            padding: 2rem;
            max-width: 600px;
        }

        .error-card {
            background: white;
            border-radius: 20px;
            padding: 3rem 2rem;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
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

        .error-code {
            font-size: 8rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1;
            margin-bottom: 1rem;
        }

        .error-icon {
            font-size: 6rem;
            color: #764ba2;
            margin-bottom: 1.5rem;
            animation: bounce 2s infinite;
        }

        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-20px);
            }
        }

        .error-title {
            font-size: 2rem;
            font-weight: 700;
            color: #2d3748;
            margin-bottom: 1rem;
        }

        .error-message {
            font-size: 1.1rem;
            color: #6c757d;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .btn-home {
            display: inline-block;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 50%, #f093fb 100%);
            color: white;
            padding: 0.85rem 2.5rem;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(118, 75, 162, 0.3);
        }

        .btn-home:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(118, 75, 162, 0.4);
            color: white;
            text-decoration: none;
        }

        .btn-home i {
            margin-right: 0.5rem;
        }

        .btn-back {
            display: inline-block;
            color: #764ba2;
            padding: 0.85rem 2rem;
            text-decoration: none;
            font-weight: 600;
            margin-top: 1rem;
            transition: all 0.3s ease;
        }

        .btn-back:hover {
            color: #667eea;
            text-decoration: none;
        }

        .suggestions {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e2e8f0;
        }

        .suggestions h3 {
            font-size: 1.2rem;
            color: #2d3748;
            margin-bottom: 1rem;
            font-weight: 600;
        }

        .suggestions ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .suggestions li {
            margin-bottom: 0.75rem;
        }

        .suggestions a {
            color: #764ba2;
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
        }

        .suggestions a:hover {
            color: #667eea;
            text-decoration: underline;
        }

        .suggestions i {
            margin-right: 0.5rem;
            font-size: 0.9rem;
        }
    </style>
</head>

<body>
    <div class="error-container">
        <div class="error-card">
            <!-- Icône animée -->
            <div class="error-icon">
                <i class="fas fa-search"></i>
            </div>

            <!-- Code d'erreur -->
            <div class="error-code">404</div>

            <!-- Titre -->
            <h1 class="error-title">Page Non Trouvée</h1>

            <!-- Message -->
            <p class="error-message">
                Désolé, la page que vous recherchez n'existe pas ou a été déplacée.
            </p>

            <!-- Boutons -->
            <div class="mt-4">
                <a href="<?php echo e(route('index')); ?>" class="btn-home">
                    <i class="fas fa-home"></i>
                    Retour à l'Accueil
                </a>
                <br>
                <a href="javascript:history.back()" class="btn-back">
                    <i class="fas fa-arrow-left"></i>
                    Page Précédente
                </a>
            </div>

            <!-- Suggestions -->
            <div class="suggestions">
                <h3>Pages Utiles :</h3>
                <ul>
                    <li>
                        <a href="<?php echo e(route('index')); ?>">
                            <i class="fas fa-tachometer-alt"></i>
                            Tableau de Bord
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('pos.vente')); ?>">
                            <i class="fas fa-cash-register"></i>
                            Point de Vente
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('produits.index')); ?>">
                            <i class="fas fa-warehouse"></i>
                            Gestion du Stock
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo e(route('clients.index')); ?>">
                            <i class="fas fa-users"></i>
                            Liste des Clients
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
</body>
</html><?php /**PATH C:\Users\marwan\clinique\resources\views/errors/404.blade.php ENDPATH**/ ?>