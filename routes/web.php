<?php
// Suppression réparation POS
Route::delete('/pos/reparation/{id}', [App\Http\Controllers\POSController::class, 'deleteRepair'])->name('pos.deleteRepair');
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UtilisateursController;
use App\Http\Controllers\ClientsController;
use App\Http\Controllers\ProduitsController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\CategorieController;
use App\Models\Produits;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\FournisseurController;
use App\Http\Controllers\POSController;

// POS Réparation dédié
Route::get('/pos/reparation', [App\Http\Controllers\POSController::class, 'reparationIndex'])->name('pos.reparation');
// POS Vente dédié
Route::get('/pos/vente', [App\Http\Controllers\POSController::class, 'venteIndex'])->name('pos.vente');

// Routes pour finaliser la vente (brouillon, suspendre, crédit, paiement multiple)
Route::post('/pos/draft', [App\Http\Controllers\POSController::class, 'draft'])->name('pos.draft');
Route::post('/pos/suspend', [App\Http\Controllers\POSController::class, 'suspend'])->name('pos.suspend');
Route::post('/pos/credit', [App\Http\Controllers\POSController::class, 'credit'])->name('pos.credit');
Route::post('/pos/multiple', [App\Http\Controllers\POSController::class, 'multiple'])->name('pos.multiple');
// Route pour paiement en espèces (vente)
Route::post('/pos/cash', [App\Http\Controllers\POSController::class, 'cash'])->name('pos.cash');
// Route pour enregistrer un devis (citation) via AJAX
Route::post('/pos/quote', [App\Http\Controllers\POSController::class, 'quote'])->name('pos.quote');

// Routes publiques (non protégées)
Route::get('/back/login', [LoginController::class, 'login'])->name('login');
Route::post('/login_valid', [LoginController::class, 'login_valid'])->name('login_valid');
Route::get('/back/register', [RegisterController::class, 'create'])->name('register');
Route::post('/back/register', [RegisterController::class, 'store'])->name('register.store');

// Page d'accueil publique


// Page home publique (à adapter si besoin)
Route::get('/home', function () {
    return view('home');
})->name('home');

// Routes protégées par authentification
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        $ventesMois = Produits::where('type', 'vente')
            ->whereMonth('created_at', now()->month)
            ->sum('prix_vente');
        $benefices = 0; 
        $reparations = 0; 
        $ruptures = Produits::where('quantite', 0)->count();
        $nouveauxClients = Utilisateur::where('role', 'client')
            ->whereMonth('created_at', now()->month)
            ->count();
        return view('index', compact('ventesMois', 'benefices', 'reparations', 'ruptures', 'nouveauxClients'));
    })->name('index');

    // Utilisateurs
    Route::resource('utilisateurs', UtilisateursController::class);

    // Clients
    Route::resource('clients', ClientsController::class);

    // Produits
    Route::get('/produits', [ProduitsController::class, 'index'])->name('produits.index');
    Route::get('/produits/create', [ProduitsController::class, 'create'])->name('produits.create');
    Route::post('/produits', [ProduitsController::class, 'store'])->name('produits.store');
    Route::get('/produits/achat', [ProduitsController::class, 'indexAchat'])->name('produits.achat');
    Route::get('/produits/vente', [ProduitsController::class, 'indexVente'])->name('produits.vente');
    Route::get('/produits/{id}/edit', [ProduitsController::class, 'edit'])->name('produits.edit');
    Route::put('/produits/{id}', [ProduitsController::class, 'update'])->name('produits.update');
    Route::delete('/produits/{id}', [ProduitsController::class, 'destroy'])->name('produits.destroy');
    Route::get('/produits/{id}', [ProduitsController::class, 'show'])->name('produits.show');
    Route::get('/produits/search', [ProduitsController::class, 'searchByName'])->name('produits.searchByName');
    Route::get('/produits/{id}/commandes', [ProduitsController::class, 'commandes'])->name('produits.commandes');
    Route::get('/produits/{id}/commandes/create', [ProduitsController::class, 'createCommande'])->name('produits.commandes.create');
    Route::post('/produits/{id}/commandes', [ProduitsController::class, 'storeCommande'])->name('produits.commandes.store');
    Route::get('/produits/{produit}/barcode', [ProduitsController::class, 'barcode'])->name('produits.barcode');
    Route::get('/produits/{produit}/ticket', [ProduitsController::class, 'printTicket'])->name('produits.ticket');
    Route::get('/produits/{produit}/facture-achat', [ProduitsController::class, 'printFactureAchat'])->name('produits.facture-achat');

    // Vente
    Route::post('/ventes', [VenteController::class, 'storeVente'])->name('ventes.store');
    Route::resource('ventes', VenteController::class);
    Route::get('/ventes/{vente}/recu', [VenteController::class, 'printRecu'])->name('ventes.recu');
    Route::get('/ventes/{vente}/facture', [VenteController::class, 'printFacture'])->name('ventes.facture');

    // API Routes pour les données AJAX
    Route::get('/api/produits/{produit}', [ProduitsController::class, 'apiShow'])->name('api.produits.show');

    // Catégories
    Route::get('/categories/search', [CategorieController::class, 'searchByName'])->name('categories.searchByName');
    Route::resource('categories', CategorieController::class);

    // Fournisseurs
    Route::get('/fournisseurs/search', [FournisseurController::class, 'searchByName'])->name('fournisseurs.searchByName');
    Route::resource('fournisseurs', FournisseurController::class);

    // Réparations
    Route::resource('reparation', \App\Http\Controllers\ReparationController::class);
    Route::get('/reparation/search', [\App\Http\Controllers\ReparationController::class, 'search'])->name('reparation.search');
    Route::get('/reparation/{reparation}/barcode', [\App\Http\Controllers\ReparationController::class, 'barcode'])->name('reparation.barcode');

    // POS
    Route::get('/pos', [App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
    Route::post('/pos', [App\Http\Controllers\POSController::class, 'store'])->name('pos.store');
    Route::post('/pos/repair', [App\Http\Controllers\POSController::class, 'storeRepair'])->name('pos.storeRepair');
    
});
