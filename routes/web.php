<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\CommandeController;
use App\Http\Controllers\ReparationController;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| Routes publiques (Authentification)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {
    Route::get('/back/login', [LoginController::class, 'login'])->name('login');
    Route::post('/login_valid', [LoginController::class, 'login_valid'])->name('login_valid');
});


/*
|--------------------------------------------------------------------------
| Routes protégées (Authentification requise)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin:admin,vendeur'])->group(function () {

    /*
    |----------------------------------------------------------------------
    | Dashboard
    |----------------------------------------------------------------------
    */
    Route::get('/', function () {
        $ventesMois = \App\Models\Produits::where('type', 'vente')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('prix_vente');
            
        $benefices = \App\Models\Produits::where('type', 'vente')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum(DB::raw('prix_vente - prix_achat'));
            
        $reparations = \App\Models\Reparation::where('etat', 'en_cours')->count();
        
        $ruptures = \App\Models\Produits::where('quantite', '<=', 0)
            ->orWhere('quantite', '<=', 5)
            ->count();
            
        $nouveauxClients = \App\Models\Utilisateur::where('role', 'client')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
            
        return view('index', compact('ventesMois', 'benefices', 'reparations', 'ruptures', 'nouveauxClients'));
    })->name('index');

    /*
    |----------------------------------------------------------------------
    | Déconnexion
    |----------------------------------------------------------------------
    */
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    /*
    |----------------------------------------------------------------------
    | Réparations
    |----------------------------------------------------------------------
    */
    Route::prefix('reparation')->name('reparation.')->group(function () {
        Route::get('/', [ReparationController::class, 'index'])->name('index');
        Route::get('/create', [ReparationController::class, 'create'])->name('create');
        Route::post('/', [ReparationController::class, 'store'])->name('store');
        Route::get('/search', [ReparationController::class, 'search'])->name('search');
        Route::get('/export', [ReparationController::class, 'export'])->name('export');
        
        Route::get('/{reparation}', [ReparationController::class, 'show'])->name('show');
        Route::get('/{reparation}/edit', [ReparationController::class, 'edit'])->name('edit');
        Route::put('/{reparation}', [ReparationController::class, 'update'])->name('update');
        Route::patch('/{reparation}/status', [ReparationController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{reparation}', [ReparationController::class, 'destroy'])->name('destroy');
        Route::get('/{reparation}/barcode', [ReparationController::class, 'barcode'])->name('barcode');
        Route::get('/{reparation}/ticket', [ReparationController::class, 'ticket'])->name('ticket');
    });

    /*
    |----------------------------------------------------------------------
    | Utilisateurs
    |----------------------------------------------------------------------
    */
    Route::resource('utilisateurs', UtilisateursController::class)->except(['show']);
    Route::get('utilisateurs/{id}', [UtilisateursController::class, 'show'])->name('utilisateurs.show');

    /*
    |----------------------------------------------------------------------
    | Clients
    |----------------------------------------------------------------------
    */
    Route::prefix('clients')->name('clients.')->group(function () {
        Route::get('/', [ClientsController::class, 'index'])->name('index');
        Route::get('/create', [ClientsController::class, 'create'])->name('create');
        Route::post('/', [ClientsController::class, 'store'])->name('store');
        Route::get('/search', [ClientsController::class, 'search'])->name('search');
        
        Route::get('/{id}', [ClientsController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [ClientsController::class, 'edit'])->name('edit');
        Route::put('/{id}', [ClientsController::class, 'update'])->name('update');
        Route::delete('/{id}', [ClientsController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Produits
    |----------------------------------------------------------------------
    */
    Route::prefix('produits')->name('produits.')->group(function () {
        Route::get('/', [ProduitsController::class, 'index'])->name('index');
        Route::get('/create', [ProduitsController::class, 'create'])->name('create');
        Route::post('/', [ProduitsController::class, 'store'])->name('store');
        Route::get('/search', [ProduitsController::class, 'search'])->name('search');
        Route::get('/tickets', [ProduitsController::class, 'printTickets'])->name('tickets');
        Route::get('/achat', [ProduitsController::class, 'indexAchat'])->name('achat');
        Route::get('/vente', [ProduitsController::class, 'indexVente'])->name('vente');
        
        Route::get('/{produit}/barcode', [ProduitsController::class, 'barcode'])->name('barcode');
        Route::get('/{produit}/ticket', [ProduitsController::class, 'printSingleTicket'])->name('ticket');
        Route::get('/{produit}/facture-achat', [ProduitsController::class, 'factureAchat'])->name('facture-achat');
        Route::get('/{produit}/edit', [ProduitsController::class, 'edit'])->name('edit');
        Route::put('/{produit}', [ProduitsController::class, 'update'])->name('update');
        Route::delete('/{produit}', [ProduitsController::class, 'destroy'])->name('destroy');
        Route::get('/{produit}', [ProduitsController::class, 'show'])->name('show');
    });

    /*
    |----------------------------------------------------------------------
    | Ventes
    |----------------------------------------------------------------------
    */
    Route::prefix('ventes')->name('ventes.')->group(function () {
        Route::get('/', [VenteController::class, 'index'])->name('index');
        Route::get('/create', [VenteController::class, 'create'])->name('create');
        Route::post('/', [VenteController::class, 'store'])->name('store');
        
        Route::get('/{vente}', [VenteController::class, 'show'])->name('show');
        Route::get('/{vente}/edit', [VenteController::class, 'edit'])->name('edit');
        Route::put('/{vente}', [VenteController::class, 'update'])->name('update');
        Route::delete('/{vente}', [VenteController::class, 'destroy'])->name('destroy');
        Route::get('/{vente}/recu', [VenteController::class, 'printRecu'])->name('recu');
        Route::get('/{vente}/facture', [VenteController::class, 'printFacture'])->name('facture');
    });

    /*
    |----------------------------------------------------------------------
    | Catégories
    |----------------------------------------------------------------------
    */
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [CategorieController::class, 'index'])->name('index');
        Route::get('/create', [CategorieController::class, 'create'])->name('create');
        Route::post('/', [CategorieController::class, 'store'])->name('store');
        Route::get('/search', [CategorieController::class, 'searchByName'])->name('searchByName');
        
        Route::get('/{categorie}', [CategorieController::class, 'show'])->name('show');
        Route::get('/{categorie}/edit', [CategorieController::class, 'edit'])->name('edit');
        Route::put('/{categorie}', [CategorieController::class, 'update'])->name('update');
        Route::delete('/{categorie}', [CategorieController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Fournisseurs
    |----------------------------------------------------------------------
    */
    Route::prefix('fournisseurs')->name('fournisseurs.')->group(function () {
        Route::get('/', [FournisseurController::class, 'index'])->name('index');
        Route::get('/create', [FournisseurController::class, 'create'])->name('create');
        Route::post('/', [FournisseurController::class, 'store'])->name('store');
        Route::get('/search', [FournisseurController::class, 'searchByName'])->name('searchByName');
        
        Route::get('/{fournisseur}', [FournisseurController::class, 'show'])->name('show');
        Route::get('/{fournisseur}/edit', [FournisseurController::class, 'edit'])->name('edit');
        Route::put('/{fournisseur}', [FournisseurController::class, 'update'])->name('update');
        Route::delete('/{fournisseur}', [FournisseurController::class, 'destroy'])->name('destroy');
    });

    /*
    |----------------------------------------------------------------------
    | Commandes d'achat
    |----------------------------------------------------------------------
    */
    Route::prefix('commandes')->name('commandes.')->group(function () {
        Route::get('/', [CommandeController::class, 'index'])->name('index');
        Route::get('/create', [CommandeController::class, 'create'])->name('create');
        Route::post('/', [CommandeController::class, 'store'])->name('store');
        Route::get('/search', [CommandeController::class, 'search'])->name('search');
        Route::get('/search-products', [CommandeController::class, 'searchProducts'])->name('search.products');
        Route::get('/product/{id}', [CommandeController::class, 'getProductForCommande'])->name('get.product');
        
        Route::get('/{commande}', [CommandeController::class, 'show'])->name('show');
        Route::get('/{commande}/edit', [CommandeController::class, 'edit'])->name('edit');
        Route::put('/{commande}', [CommandeController::class, 'update'])->name('update');
        Route::delete('/{commande}', [CommandeController::class, 'destroy'])->name('destroy');
        Route::get('/{commande}/facture', [CommandeController::class, 'printFacture'])->name('facture');
        Route::get('/{commande}/tickets-produits', [CommandeController::class, 'printProductTickets'])->name('tickets-produits');
    });

    /*
    |----------------------------------------------------------------------
    | Point de Vente (POS)
    |----------------------------------------------------------------------
    */
Route::prefix('pos')->name('pos.')->group(function () {
    // Redirection par défaut
    Route::get('/', function() {
        return redirect()->route('pos.vente');
    })->name('index');
    
    // VENTE
    Route::get('/vente', [POSController::class, 'venteIndex'])->name('vente');
    Route::post('/vente', [POSController::class, 'store'])->name('storeVente');
    
    // Routes pour le panier basique
    Route::post('/cash', [POSController::class, 'store'])->name('cash');
    Route::post('/credit', [POSController::class, 'store'])->name('credit');
    
    // RÉPARATION
    Route::get('/reparation', [POSController::class, 'reparationIndex'])->name('reparation');
    Route::post('/reparation/store', [POSController::class, 'storeRepair'])->name('storeRepair');
    Route::delete('/reparation/{id}', [POSController::class, 'deleteRepair'])->name('deleteRepair');
    Route::get('/reparation/{id}/ticket', [POSController::class, 'repairTicket'])->name('repairTicket');
    
    
    // API & UTILITAIRES
    Route::get('/barcode/{code}', [POSController::class, 'generateBarcodeApi'])->name('barcode');
    Route::post('/search-repair', [POSController::class, 'searchRepairByCode'])->name('searchRepair');
});
    /*
    |----------------------------------------------------------------------
    | Routes API pour AJAX/JSON
    |----------------------------------------------------------------------
    */
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/produits/search', [ProduitsController::class, 'search'])->name('produits.search');
        Route::get('/clients/search', [ClientsController::class, 'search'])->name('clients.search');
        Route::get('/categories/search', [CategorieController::class, 'searchByName'])->name('categories.search');
        Route::get('/fournisseurs/search', [FournisseurController::class, 'searchByName'])->name('fournisseurs.search');
    });
});

/*
|--------------------------------------------------------------------------
| Route de secours (404)
|--------------------------------------------------------------------------
*/
Route::fallback(function () {
    if (request()->expectsJson()) {
        return response()->json([
            'message' => 'Route non trouvée',
            'error' => 'Not Found'
        ], 404);
    }
    
    return response()->view('errors.404', [], 404);
});