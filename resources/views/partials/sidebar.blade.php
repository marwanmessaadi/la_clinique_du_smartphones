<!-- Sidebar -->
<ul class="navbar-nav bg-sidebar sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('index') }}" style="background: #ffffffd1;">
        <div class="sidebar-brand-icon">
            <img src="{{ asset('img/LOGO.png') }}" alt="Logo" style="width: 70px; height: 64px; object-fit:cover; border-radius:40px; ">
        </div>

    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item active">
        <a class="nav-link" href="{{ route('index') }}">
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

    <!-- Nav Item - Utilisateurs -->
    <li class="nav-item">
        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseUtilisateurs" 
           aria-expanded="true" aria-controls="collapseUtilisateurs">
            <i class="fas fa-fw fa-users"></i>
            <span>Utilisateurs</span>
        </a>
        <div id="collapseUtilisateurs" class="collapse" aria-labelledby="headingUtilisateurs" data-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <h6 class="collapse-header">Gestion des utilisateurs:</h6>
                <a class="collapse-item" href="{{ route('utilisateurs.index') }}">Liste des utilisateurs</a>
                <a class="collapse-item" href="{{ route('utilisateurs.create') }}">Créer un utilisateur</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Clients -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('clients.index') }}">
            <i class="fas fa-fw fa-user-tie"></i>
            <span>Clients</span>
        </a>
    </li>

    <!-- Nav Item - Fournisseurs -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('fournisseurs.index') }}">
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
        <a class="nav-link" href="{{ route('pos.vente') }}">
            <i class="fas fa-fw fa-cash-register"></i>
            <span>POS Vente</span>
        </a>
    </li>

    <!-- Nav Item - POS Réparation -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('pos.reparation') }}">
            <i class="fas fa-fw fa-tools"></i>
            <span>POS Réparation</span>
        </a>
    </li>

    <!-- Nav Item - Ventes -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('ventes.index') }}">
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
                <a class="collapse-item" href="{{ route('commandes.index') }}">Liste des commandes</a>
                <a class="collapse-item" href="{{ route('produits.create') }}">Nouvelle commande</a>
            </div>
        </div>
    </li>

    <!-- Nav Item - Produits d'Achat -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('produits.achat') }}">
            <i class="fas fa-fw fa-boxes"></i>
            <span>Produits d'Achat</span>
        </a>
    </li>

    <!-- Nav Item - Stock -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('produits.index') }}">
            <i class="fas fa-fw fa-warehouse"></i>
            <span>Gestion du Stock</span>
        </a>
    </li>

    <!-- Nav Item - Catégories -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('categories.index') }}">
            <i class="fas fa-fw fa-list"></i>
            <span>Catégories</span>
        </a>
    </li>

    <!-- Nav Item - Réparations -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('reparation.index') }}">
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