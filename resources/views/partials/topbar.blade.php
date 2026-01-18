{{-- filepath: resources/views/partials/topbar.blade.php --}}
<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

    <!-- Sidebar Toggle (Topbar) -->
    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
        <i class="fa fa-bars"></i>
    </button>

    <!-- Left Side Content -->
    <div class="d-flex align-items-center">
        <a href="{{ route('pos.vente') }}" class="btn btn-primary btn-sm mr-3">
            <i class="fas fa-cash-register"></i> POS
        </a>
        <span class="mr-3 text-gray-600">
            <i class="fas fa-user-circle fa-lg mr-2"></i>
            {{ Auth::user()->prenom ?? 'Utilisateur' }} {{ Auth::user()->nom ?? '' }}
            <small class="text-muted">({{ ucfirst(Auth::user()->role ?? 'N/A') }})</small>
        </span>
        <span class="mr-3 text-gray-600">
            <i class="fas fa-calendar-alt fa-lg mr-2"></i>
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Bouton de déconnexion DIRECT -->
        <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm" 
                        onclick="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-1"></i>
                    Déconnexion
                </button>
            </form>
        </li>
    </ul>

</nav>

<style>
/* Style pour que le bouton de déconnexion ressemble à un lien */
#logout-form button.dropdown-item {
    background: none;
    border: none;
    width: 100%;
    text-align: left;
    cursor: pointer;
    padding: 0.5rem 1.5rem;
    display: block;
    font-weight: 400;
    color: #858796;
}

#logout-form button.dropdown-item:hover {
    background-color: #f8f9fc;
    color: #4e73df;
}

.topbar-divider {
    width: 0;
    border-right: 1px solid #e3e6f0;
    height: calc(4.375rem - 2rem);
    margin: auto 1rem;
}
</style>