<!-- Sidebar -->
<ul class="navbar-nav bg-sidebar sidebar sidebar-dark accordion toggled" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo e(route('index')); ?>">
        <div class="sidebar-brand-icon" style="background: white; border-radius: 50%; padding: 5px;">
            <img src="<?php echo e(asset('img/LOGO.png')); ?>" alt="Logo" style="width: 50px; height: 50px; object-fit: contain;">
        </div>
        <div class="sidebar-brand-text mx-2">La Clinique du Smartphone</div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="<?php echo e(route('index')); ?>">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Tableau de bord</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Administration
    </div>

    <!-- Nav Item - Utilisateurs avec icônes -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilisateurs" 
           aria-expanded="true" aria-controls="collapseUtilisateurs">
            <i class="fas fa-fw fa-users"></i>
            <span>Utilisateurs</span>
        </a>
        <div id="collapseUtilisateurs" class="collapse" aria-labelledby="headingUtilisateurs" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des utilisateurs:</h6>
                <a class="collapse-item" href="<?php echo e(route('utilisateurs.index')); ?>">
                    <i class="fas fa-list mr-2"></i> Liste des utilisateurs
                </a>
                <a class="collapse-item" href="<?php echo e(route('utilisateurs.create')); ?>">
                    <i class="fas fa-user-plus mr-2"></i> Créer un utilisateur
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-user-shield mr-2"></i> Rôles & Permissions
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-user-clock mr-2"></i> Activité des utilisateurs
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Clients avec icônes -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseClients" 
           aria-expanded="true" aria-controls="collapseClients">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Clients</span>
        </a>
        <div id="collapseClients" class="collapse" aria-labelledby="headingClients" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des clients:</h6>
                <a class="collapse-item" href="<?php echo e(route('clients.index')); ?>">
                    <i class="fas fa-address-book mr-2"></i> Liste des clients
                </a>
                <a class="collapse-item" href="<?php echo e(route('clients.create')); ?>">
                    <i class="fas fa-user-plus mr-2"></i> Ajouter un client
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-chart-line mr-2"></i> Statistiques clients
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-history mr-2"></i> Historique des achats
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Fournisseurs -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('fournisseurs.index')); ?>">
            <i class="fas fa-fw fa-truck"></i>
            <span>Fournisseurs</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Gestion Commerciale
    </div>

    <!-- Nav Item - POS Vente -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('pos.vente')); ?>">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>POS Vente</span>
        </a>
    </li>

    <!-- Nav Item - POS Réparation -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('pos.reparation')); ?>">
            <i class="fas fa-fw fa-tools"></i>
            <span>POS Réparation</span>
        </a>
    </li>

    <!-- Nav Item - Ventes -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('ventes.index')); ?>">
            <i class="fas fa-fw fa-shopping-cart"></i>
            <span>Gérer les Ventes</span>
        </a>
    </li>

    <!-- Nav Item - Commandes d'Achat -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseCommandes" 
           aria-expanded="true" aria-controls="collapseCommandes">
            <i class="fas fa-fw fa-file-invoice"></i>
            <span>Commandes d'Achat</span>
        </a>
        <div id="collapseCommandes" class="collapse" aria-labelledby="headingCommandes" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des commandes:</h6>
                <a class="collapse-item" href="<?php echo e(route('commandes.index')); ?>">
                    <i class="fas fa-list-alt mr-2"></i> Liste des commandes
                </a>
                <a class="collapse-item" href="<?php echo e(route('produits.create')); ?>">
                    <i class="fas fa-plus-circle mr-2"></i> Nouvelle commande
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-file-invoice-dollar mr-2"></i> Factures
                </a>
                <a class="collapse-item" href="#">
                    <i class="fas fa-chart-bar mr-2"></i> Statistiques
                </a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Produits d'Achat -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('produits.achat')); ?>">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Produits d'Achat</span>
        </a>
    </li>

    <!-- Nav Item - Stock -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('produits.index')); ?>">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Gestion du Stock</span>
        </a>
    </li>

    <!-- Nav Item - Catégories -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('categories.index')); ?>">
            <i class="fas fa-fw fa-list"></i>
            <span>Catégories</span>
        </a>
    </li>

    <!-- Nav Item - Réparations -->
    <li class="nav-item">
        <a class="nav-link" href="<?php echo e(route('reparation.index')); ?>">
            <i class="fas fa-fw fa-wrench"></i>
            <span>Réparations</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
<!-- End of Sidebar -->

<?php $__env->startPush('styles'); ?>
<style>
    /* Sidebar réduite par défaut */
    body:not(.sidebar-toggled) .sidebar {
        width: 6.5rem !important;
    }
    
    body:not(.sidebar-toggled) .sidebar .nav-link span,
    body:not(.sidebar-toggled) .sidebar .sidebar-heading,
    body:not(.sidebar-toggled) .sidebar .sidebar-brand-text {
        display: none;
    }
    
    body:not(.sidebar-toggled) .sidebar .sidebar-brand-icon {
        padding: 8px;
    }
    
    body:not(.sidebar-toggled) .sidebar .sidebar-brand-icon img {
        width: 40px;
        height: 40px;
    }
    
    body:not(.sidebar-toggled) .sidebar .nav-link {
        text-align: center;
        padding: 0.75rem 1rem;
    }
    
    body:not(.sidebar-toggled) .sidebar .nav-link i {
        margin-right: 0;
        font-size: 1.2rem;
    }
    
    body:not(.sidebar-toggled) .sidebar .sidebar-divider {
        text-align: center;
        margin: 1rem auto;
    }
    
    body:not(.sidebar-toggled) .sidebar .collapse-inner,
    body:not(.sidebar-toggled) .sidebar .collapse-header {
        display: none !important;
    }
    
    /* Tooltips pour la version réduite */
    body:not(.sidebar-toggled) .sidebar .nav-item {
        position: relative;
    }
    
    body:not(.sidebar-toggled) .sidebar .nav-item:hover::after {
        content: attr(data-title);
        position: absolute;
        left: 100%;
        top: 50%;
        transform: translateY(-50%);
        background: #343a40;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        white-space: nowrap;
        z-index: 1000;
        margin-left: 10px;
        font-size: 0.85rem;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    }
    
    /* Data-title pour les éléments */
    .sidebar .nav-item[data-title]::after {
        content: none;
    }
    
    /* Mode étendu (quand on clique sur le toggle) */
    body.sidebar-toggled .sidebar {
        width: 16rem !important;
    }
    
    /* Transitions fluides */
    .sidebar,
    .sidebar .nav-link span,
    .sidebar .sidebar-heading,
    .sidebar .sidebar-brand-text {
        transition: all 0.3s ease;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Ajouter data-title pour les tooltips
    document.querySelectorAll('.sidebar .nav-item').forEach(item => {
        const link = item.querySelector('.nav-link');
        if (link) {
            const text = link.querySelector('span');
            if (text) {
                item.setAttribute('data-title', text.textContent.trim());
            }
        }
    });
    
    // Ajouter data-title pour les sous-menus
    document.querySelectorAll('.sidebar .collapse-item').forEach(item => {
        const text = item.textContent.trim();
        item.setAttribute('data-title', text);
    });
    
    // Mode réduit par défaut
    if (!document.body.classList.contains('sidebar-toggled')) {
        // Cacher tous les collapse-inner
        document.querySelectorAll('.collapse-inner').forEach(el => {
            el.style.display = 'none';
        });
        
        // Empêcher l'ouverture des collapses
        document.querySelectorAll('[data-toggle="collapse"]').forEach(trigger => {
            trigger.addEventListener('click', function(e) {
                if (!document.body.classList.contains('sidebar-toggled')) {
                    e.preventDefault();
                    return false;
                }
            });
        });
    }
    
    // Toggle du sidebar (bouton unique)
    document.getElementById('sidebarToggle').addEventListener('click', function(e) {
        e.preventDefault();
        
        document.body.classList.toggle('sidebar-toggled');
        
        // Si on passe en mode étendu, afficher les collapses
        if (document.body.classList.contains('sidebar-toggled')) {
            // Réactiver les collapses
            document.querySelectorAll('[data-toggle="collapse"]').forEach(trigger => {
                trigger.removeEventListener('click', preventClick);
            });
        } else {
            // Mode réduit - cacher les collapses
            document.querySelectorAll('.collapse.show').forEach(collapse => {
                bootstrap.Collapse.getInstance(collapse).hide();
            });
            
            // Empêcher l'ouverture des collapses
            document.querySelectorAll('[data-toggle="collapse"]').forEach(trigger => {
                trigger.addEventListener('click', preventClick);
            });
        }
    });
    
    function preventClick(e) {
        if (!document.body.classList.contains('sidebar-toggled')) {
            e.preventDefault();
            return false;
        }
    }
});
</script>
<?php $__env->stopPush(); ?><?php /**PATH C:\Users\marwan\clinique\resources\views/partials/sidebar.blade.php ENDPATH**/ ?>