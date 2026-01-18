<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Back-office La Clinique du Smartphone">
    <meta name="author" content="La Clinique du Smartphone">

    <title><?php echo $__env->yieldContent('title', 'La Clinique du Smartphone'); ?></title>

    <link rel="icon" type="image/x-icon" href="<?php echo e(asset('favicon.ico')); ?>">
    <link href="<?php echo e(asset('vendor/fontawesome-free/css/all.min.css')); ?>" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="<?php echo e(asset('/back/css/sb-admin-2.min.css')); ?>" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    <style>
        .sidebar-brand {
            background: #000000;
        }
        .bg-gradient-primary {
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%) !important;
        }
        .bg-sidebar {
            background-color: rgba(44, 62, 80, 0.9) !important;
            backdrop-filter: blur(10px);
        }
        .sidebar {
            width: 220px !important;
            box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
        }
        .sidebar .sidebar-brand {
            height: 4rem;
        }
        .sidebar .nav-item .nav-link {
            transition: all 0.3s ease;
        }
        .sidebar .nav-item .nav-link:hover {
            transform: translateX(5px);
        }
        .sidebar-dark .nav-item .nav-link {
            color: rgba(255, 255, 255, 0.8);
        }
        .sidebar-dark .nav-item .nav-link:hover {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        .sidebar-dark .nav-item.active .nav-link {
            color: #fff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        }
        .card.border-left-primary {
            border-left: 4px solid;
            border-image: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%) 1;
        }
        .card.border-left-info {
            border-left: 4px solid;
            border-image: linear-gradient(135deg, #17a2b8 0%, #20c997 100%) 1;
        }
        .card.border-left-secondary {
            border-left: 4px solid;
            border-image: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%) 1;
        }
        .text-primary {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-info {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .text-secondary {
            background: linear-gradient(135deg, #fd7e14 0%, #ffc107 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .btn-primary {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            border: none;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a32a3 0%, #d6206e 100%);
        }
        .btn-success {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            border: none;
        }
        .btn-success:hover {
            background: linear-gradient(135deg, #138496 0%, #17a2b8 100%);
        }
        .btn-info {
            background: linear-gradient(135deg, #17a2b8 0%, #20c997 100%);
            border: none;
        }
        .btn-info:hover {
            background: linear-gradient(135deg, #138496 0%, #17a2b8 100%);
        }
        .card-header {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
        }
        .card-header h6 {
            color: #ffffff !important;
        }
        .badge-success {
            background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
            color: white;
        }
        .badge-warning {
            background: linear-gradient(135deg, #fa709a 0%, #fee140 100%);
            color: white;
        }
        .badge-danger {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
        }
        .table thead th {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
        }
        .badge { 
            padding: 0.5em 0.75em; 
            font-size: 0.75em; 
            font-weight: 600;
            border-radius: 0.25rem;
        }
        .table-bordered { 
            border: 1px solid #e3e6f0; 
        }
        .table tbody tr:hover {
            background: linear-gradient(135deg, rgba(111, 66, 193, 0.05) 0%, rgba(232, 62, 140, 0.05) 100%);
        }
        .page-header {
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            color: white;
            padding: 2rem 0;
            margin-bottom: 2rem;
            border-radius: 12px;
            text-align: center;
        }
        .page-title {
            font-size: 2rem;
            font-weight: 600;
            margin: 0;
        }
        .page-subtitle {
            font-size: 1rem;
            margin: 0.5rem 0 0 0;
            opacity: 0.9;
        }
        .content-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 2rem;
            overflow: hidden;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: 600;
            margin: 0;
        }
        .btn-add, .btn-search, .btn-edit {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            border: none;
        }
        .btn-add:hover, .btn-search:hover, .btn-edit:hover {
            background: linear-gradient(135deg, #5a32a3 0%, #d6206e 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(111, 66, 193, 0.3);
        }
        .btn-delete {
            background: linear-gradient(135deg, #eb3349 0%, #f45c43 100%);
            color: white;
            border: none;
            padding: 0.5rem 1rem;
            border-radius: 6px;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }
        .btn-delete:hover {
            background: linear-gradient(135deg, #c82333 0%, #dc3545 100%);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(235, 51, 73, 0.3);
        }
        .form-control:focus {
            outline: none;
            border-color: #6f42c1;
            box-shadow: 0 0 0 3px rgba(111, 66, 193, 0.1);
        }
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: #6c757d;
        }
        .empty-icon {
            font-size: 4rem;
            margin-bottom: 1rem;
            opacity: 0.5;
        }
        .dashboard-container {
            padding: 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        .form-card {
            background: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            margin-bottom: 2rem;
        }
        .form-header {
            padding: 1.5rem;
            border-bottom: 1px solid #edf2f7;
            background: linear-gradient(135deg, #6f42c1 0%, #e83e8c 100%);
            color: white;
            text-align: center;
        }
        .form-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin: 0;
        }
        .form-body {
            padding: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        .form-control {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 1rem;
        }
        .badge-achat {
            background: #fffbe6;
            color: #b8860b;
            border: 1px solid #ffe58f;
        }
        .badge-vente {
            background: #e6ffed;
            color: #389e0d;
            border: 1px solid #b7eb8f;
        }
        .badge-indisponible {
            background: #fff1f0;
            color: #cf1322;
            border: 1px solid #ffa39e;
        }
        .price-column {
            text-align: right;
            font-family: monospace;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>

<body id="page-top">
    <div id="wrapper">
        <!-- Inclusion du Sidebar -->
        <?php echo $__env->make('partials.sidebar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <!-- Inclusion du Topbar -->
                <?php echo $__env->make('partials.topbar', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

                <!-- Contenu Principal -->
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>

            <!-- Inclusion du Footer -->
            <?php echo $__env->make('partials.footer', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </div>
    </div>

    <!-- Scroll to Top Button -->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Scripts -->
    <script src="<?php echo e(asset('vendor/jquery/jquery.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/bootstrap/js/bootstrap.bundle.min.js')); ?>"></script>
    <script src="<?php echo e(asset('vendor/jquery-easing/jquery.easing.min.js')); ?>"></script>
    <script src="<?php echo e(asset('js/sb-admin-2.min.js')); ?>"></script>

    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html><?php /**PATH C:\Users\marwan\clinique\resources\views/layouts/app.blade.php ENDPATH**/ ?>