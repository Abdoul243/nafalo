<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Boutique;
use App\Http\Controllers\Client;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Models\Boutique as BoutiqueModel;

// Routes d'authentification admin
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    // Anti brute-force : 5 tentatives / minute / IP
    Route::post('/login', [LoginController::class, 'login'])->middleware('throttle:5,1');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

    // Profil utilisateur
    Route::get('/profil', [App\Http\Controllers\Admin\ProfilController::class, 'index'])
        ->middleware('auth')
        ->name('profil');
    Route::prefix('profil')->name('profil.')->middleware('auth')->group(function () {
        Route::put('/update', [App\Http\Controllers\Admin\ProfilController::class, 'update'])->name('update');
        Route::put('/password', [App\Http\Controllers\Admin\ProfilController::class, 'password'])->name('password');
        Route::put('/avatar', [App\Http\Controllers\Admin\ProfilController::class, 'avatar'])->name('avatar');
        Route::put('/preferences', [App\Http\Controllers\Admin\ProfilController::class, 'preferences'])->name('preferences');
    });
    
    // Routes de réinitialisation de mot de passe
    Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');
    Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->middleware('throttle:5,1')
        ->name('password.email');
    Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');
    Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Alias attendu par le middleware auth Laravel
Route::redirect('/login', '/admin/login')->name('login');
Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])
    ->name('password.request');
Route::post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
    ->name('password.email');
Route::get('/password/reset/{token}', [ResetPasswordController::class, 'showResetForm'])
    ->name('password.reset');
Route::post('/password/reset', [ResetPasswordController::class, 'reset'])
    ->name('password.update');


// ── Pages légales ────────────────────────────────────────────────────────────
Route::get('/conditions-utilisation',    fn() => view('legal.conditions'))->name('legal.conditions');
Route::get('/politique-confidentialite', fn() => view('legal.confidentialite'))->name('legal.confidentialite');
Route::get('/mentions-legales',          fn() => view('legal.mentions'))->name('legal.mentions');
Route::get('/politique-remboursement',   fn() => view('legal.remboursement'))->name('legal.remboursement');
Route::get('/contact',                   fn() => view('legal.contact'))->name('legal.contact');

// Routes principales
Route::redirect('/', '/admin/login');

Route::get('/boutiques', function () {
    $boutiques = BoutiqueModel::where('est_active', true)
        ->orderBy('nom')
        ->get(['id', 'nom', 'description', 'domaine_personnalise']);

    return view('boutique.liste', compact('boutiques'));
})->name('boutiques.accueil');

Route::get('/media/produits/{produit}/image', [MediaController::class, 'produitImage'])
    ->name('media.produits.image');
Route::get('/media/boutiques/{boutique}/logo', [MediaController::class, 'boutiqueLogo'])
    ->name('media.boutiques.logo');

// EntrÃ©e locale: http://127.0.0.1:8000/{domaine}/boutique
Route::get('/{domaine}/boutique', function (string $domaine) {
    session(['boutique_domaine' => $domaine]);
    return redirect('/boutique');
})->where('domaine', '[A-Za-z0-9\\.-]+');

// Routes admin (incluses via admin.php)
require __DIR__.'/admin.php';


// Routes boutique avec domaine dynamique
Route::middleware(['web', 'domaine'])->prefix('boutique')->group(function () {
    
    // Accueil boutique
    Route::get('/', [Boutique\AccueilController::class, 'index'])->name('boutique.accueil');
    
    // Produits
    Route::prefix('produits')->name('boutique.produit.')->group(function () {
        Route::get('/', [Boutique\ProduitController::class, 'index'])->name('index');
        Route::get('/{slug}', [Boutique\ProduitController::class, 'show'])->name('show');
    });
    
    // Panier
    Route::prefix('panier')->name('boutique.panier.')->group(function () {
        Route::get('/', [Boutique\PanierController::class, 'index'])->name('index');
        Route::post('/ajouter/{produit}', [Boutique\PanierController::class, 'ajouter'])->name('ajouter');
        Route::post('/mettre-a-jour', [Boutique\PanierController::class, 'mettreAJour'])->name('mettre-a-jour');
        Route::delete('/supprimer/{produit}', [Boutique\PanierController::class, 'supprimer'])->name('supprimer');
        Route::post('/code-promo', [Boutique\PanierController::class, 'appliquerCodePromo'])->name('code-promo');
        Route::delete('/code-promo', [Boutique\PanierController::class, 'supprimerCodePromo'])->name('supprimer-code-promo');
        Route::post('/abandonner', [Boutique\PanierController::class, 'abandonner'])->name('abandonner');
    });
    
    // ── Lead Magnet (produit gratuit) ─────────────────────────────────
    Route::post('/lead/{produit}/capturer', [Boutique\LeadController::class, 'capturer'])
        ->name('boutique.lead.capturer');


    // Checkout GeniusPay
    Route::prefix('checkout')->name('boutique.checkout.')->group(function () {
        // Ajout direct au panier + redirection checkout (upsell / lead merci page)
        Route::get('/produit/{id}', function ($id) {
            $produit = \App\Models\Produit::findOrFail($id);
            \Illuminate\Support\Facades\Session::put('panier_' . session('boutique_id'), [$produit->id => 1]);
            return redirect()->route('boutique.checkout.informations');
        })->name('produit');
        Route::get('/informations', [Boutique\CheckoutController::class, 'informations'])->name('informations');
        Route::post('/payer', [Boutique\CheckoutController::class, 'initierPaiement'])->name('payer');
        Route::get('/succes', [Boutique\CheckoutController::class, 'succes'])->name('succes');
        Route::get('/annulation', [Boutique\CheckoutController::class, 'annulation'])->name('annulation');
        Route::get('/callback', [Boutique\CheckoutController::class, 'callback'])->name('callback');
        Route::get('/verifier-statut', [Boutique\CheckoutController::class, 'verifierStatut'])->name('verifier-statut');
    });
    
    // Webhook Moneroo (sans CSRF)
    Route::post('/checkout/webhook/moneroo', [Boutique\CheckoutController::class, 'webhook'])
        ->name('boutique.checkout.webhook')
        ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);
    
    // Avis
    Route::prefix('avis')->name('boutique.avis.')->group(function () {
        Route::get('/produit/{produit}/create', [Boutique\AvisController::class, 'create'])->name('create');
        Route::post('/produit/{produit}', [Boutique\AvisController::class, 'store'])->name('store');
    });
    
    // Accès client
    Route::prefix('client')->name('client.')->group(function () {
        Route::get('/acces', [Client\AccesController::class, 'demande'])->name('acces.demande');
        Route::post('/acces', [Client\AccesController::class, 'envoyerCode'])->name('acces.envoyer-code');
        Route::get('/verification', [Client\AccesController::class, 'verification'])->name('acces.verification');
        Route::post('/verification', [Client\AccesController::class, 'verifierCode'])->name('acces.verifier');
        Route::post('/deconnexion', [Client\AccesController::class, 'deconnexion'])->name('acces.deconnexion');
        
        // Mes achats (protégé)
        Route::middleware('client.auth')->group(function () {
            Route::get('/mes-achats', [Client\MesAchatsController::class, 'index'])->name('mes-achats.index');
            Route::get('/mes-achats/{achat}', [Client\MesAchatsController::class, 'show'])->name('mes-achats.show');
            Route::get('/telechargement/{achat}', [Client\TelechargementController::class, 'telecharger'])->name('telechargement');

            // ── Espace membre formation ──
            Route::get('/formation/{produit}', [Client\FormationClientController::class, 'show'])->name('formation.show');
            Route::post('/lecon/{lecon}/terminer', [Client\FormationClientController::class, 'terminerLecon'])->name('formation.lecon.terminer');
            Route::get('/lecon/{lecon}/video', [Client\FormationClientController::class, 'video'])->name('formation.lecon.video');
            Route::get('/lecon/{lecon}/ressource', [Client\FormationClientController::class, 'ressource'])->name('formation.lecon.ressource');

            // ── Espace communauté ──
            Route::get('/communaute/{produit}', [Client\CommunauteController::class, 'show'])->name('communaute.show');
            Route::post('/communaute/{produit}/poster', [Client\CommunauteController::class, 'poster'])->name('communaute.poster');
        });
    });
});
// ─── Routes Super Admin ───────────────────────────────────────────────────
Route::prefix('superadmin')->name('superadmin.')->group(function () {

    // Connexion (publique)
    Route::get('/login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [App\Http\Controllers\SuperAdmin\AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login.post');
    Route::post('/logout', [App\Http\Controllers\SuperAdmin\AuthController::class, 'logout'])->name('logout');

    // Routes protégées
    Route::middleware('superadmin')->group(function () {

        // Dashboard
        Route::get('/dashboard', [App\Http\Controllers\SuperAdmin\DashboardController::class, 'index'])->name('dashboard');

        // Marchands
        Route::prefix('marchands')->name('marchands.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\MarchandController::class, 'index'])->name('index');
            Route::get('/{utilisateur}', [App\Http\Controllers\SuperAdmin\MarchandController::class, 'show'])->name('show');
            Route::patch('/{utilisateur}/toggle', [App\Http\Controllers\SuperAdmin\MarchandController::class, 'toggle'])->name('toggle');
            Route::get('/{utilisateur}/contacter', [App\Http\Controllers\SuperAdmin\MarchandController::class, 'contacter'])->name('contacter');
            Route::post('/{utilisateur}/contacter', [App\Http\Controllers\SuperAdmin\MarchandController::class, 'envoyerEmail'])->name('envoyer-email');
        });

        // Boutiques
        Route::prefix('boutiques')->name('boutiques.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\BoutiqueController::class, 'index'])->name('index');
            Route::get('/{boutique}', [App\Http\Controllers\SuperAdmin\BoutiqueController::class, 'show'])->name('show');
            Route::patch('/{boutique}/toggle', [App\Http\Controllers\SuperAdmin\BoutiqueController::class, 'toggle'])->name('toggle');
            Route::patch('/{boutique}/reassigner', [App\Http\Controllers\SuperAdmin\BoutiqueController::class, 'reassigner'])->name('reassigner');
        });

        // Transactions
        Route::prefix('transactions')->name('transactions.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\TransactionController::class, 'index'])->name('index');
            Route::get('/{transaction}', [App\Http\Controllers\SuperAdmin\TransactionController::class, 'show'])->name('show');
        });

        // ── Feature 10 : Fraudes ──────────────────────────────────────
        Route::prefix('fraudes')->name('fraudes.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\FraudeController::class, 'index'])->name('index');
            Route::post('/{transaction}/marquer', [App\Http\Controllers\SuperAdmin\FraudeController::class, 'marquer'])->name('marquer');
            Route::post('/{transaction}/blanchir', [App\Http\Controllers\SuperAdmin\FraudeController::class, 'blanchir'])->name('blanchir');
        });

        // ── Feature 12 : KYC ─────────────────────────────────────────
        Route::prefix('kycs')->name('kycs.')->group(function () {
            Route::get('/', [App\Http\Controllers\SuperAdmin\KycController::class, 'index'])->name('index');
            Route::get('/{kyc}', [App\Http\Controllers\SuperAdmin\KycController::class, 'show'])->name('show');
            Route::post('/{kyc}/approuver', [App\Http\Controllers\SuperAdmin\KycController::class, 'approuver'])->name('approuver');
            Route::post('/{kyc}/rejeter', [App\Http\Controllers\SuperAdmin\KycController::class, 'rejeter'])->name('rejeter');
            Route::get('/{kyc}/doc/{cote}', [App\Http\Controllers\SuperAdmin\KycController::class, 'telechargerDoc'])->name('doc');
        });
    });
});