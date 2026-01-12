{{-- filepath: c:\projet_de_stage_test\resources\views\partials\topbar.blade.php --}}
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
            Bienvenue, {{ Auth::user()->prenom ?? 'Utilisateur' }} {{ Auth::user()->nom ?? '' }}
        </span>
        <span class="mr-3 text-gray-600">
            <i class="fas fa-calendar-alt fa-lg mr-2"></i>
            {{ now()->format('d/m/Y') }}
        </span>
    </div>

    <!-- Topbar Navbar -->
    <ul class="navbar-nav ml-auto">
        <!-- Déconnexion -->
        <li class="nav-item">
            <a class="nav-link" href="{{ route('login') }}">
                <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                Déconnexion
            </a>
        </li>
    </ul>

</nav>
