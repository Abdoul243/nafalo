<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin;

// ── Routes publiques (sans authentification) ──────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['web'])->group(function () {
    Route::get('/register',  [Admin\RegisterController::class, 'create'])->name('register.form');
    Route::post('/register', [Admin\RegisterController::class, 'store'])->name('register');
});

// ── Routes protégées (authentification requise) ───────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['web', 'auth'])->group(function () {

    // Dashboard
    Route::get('/', [Admin\DashboardController::class, 'index'])->name('dashboard');

    // Chatbot IA admin — rate-limit (appels API Anthropic payants)
    Route::post('/chatbot', [Admin\ChatbotController::class, 'chat'])
        ->middleware('throttle:20,1')->name('chatbot');

    // IA Produits — rate-limit
    Route::prefix('ia')->name('ia.')->middleware('throttle:20,1')->group(function () {
        Route::post('/generer-page-vente', [Admin\ProduitIaController::class, 'genererPageVente'])->name('generer-page-vente');
        Route::post('/traduire',           [Admin\ProduitIaController::class, 'traduire'])->name('traduire');
        Route::post('/scorer-compatibilite', [Admin\ProduitIaController::class, 'scorerCompatibilite'])->name('scorer-compatibilite');
    });

    // Sélection boutique
    Route::get('/choisir-boutique', [Admin\BoutiqueSelectController::class, 'choisir'])
        ->name('boutiques.choisir');
    Route::get('/boutiques/{id}/select', [Admin\BoutiqueSelectController::class, 'select'])
        ->name('boutiques.select');

    // Gestion des boutiques
    Route::get('boutiques/check-domain', [Admin\BoutiqueController::class, 'checkDomain'])
        ->name('boutiques.check-domain');
    Route::resource('boutiques', Admin\BoutiqueController::class);
    Route::post('boutiques/{boutique}/toggle-activation', [Admin\BoutiqueController::class, 'toggleActivation'])
        ->name('boutiques.toggle-activation');

    // Gestion des produits
    // Écran de choix du type de produit (style Chariow)
    Route::get('produits/choisir', [Admin\ProduitController::class, 'choisirType'])
        ->name('produits.choisir');

    // Téléchargement du fichier produit par le marchand (protégé)
    Route::get('produits/{produit}/fichier', [Admin\ProduitController::class, 'telechargerFichier'])
        ->name('produits.fichier');

    // ── Formation : constructeur de programme (modules + leçons) ──
    Route::prefix('produits/{produit}/formation')->name('produits.formation.')->group(function () {
        Route::get('/', [Admin\FormationController::class, 'programme'])->name('programme');
        Route::post('/modules', [Admin\FormationController::class, 'storeModule'])->name('modules.store');
    });
    Route::put('formation/modules/{module}', [Admin\FormationController::class, 'updateModule'])->name('produits.formation.modules.update');
    Route::delete('formation/modules/{module}', [Admin\FormationController::class, 'destroyModule'])->name('produits.formation.modules.destroy');
    Route::post('formation/modules/{module}/lecons', [Admin\FormationController::class, 'storeLecon'])->name('produits.formation.lecons.store');
    Route::put('formation/lecons/{lecon}', [Admin\FormationController::class, 'updateLecon'])->name('produits.formation.lecons.update');
    Route::delete('formation/lecons/{lecon}', [Admin\FormationController::class, 'destroyLecon'])->name('produits.formation.lecons.destroy');

    // ── Bundle : composition du pack ──
    Route::get('produits/{produit}/bundle', [Admin\BundleController::class, 'gestion'])->name('produits.bundle.gestion');
    Route::post('produits/{produit}/bundle', [Admin\BundleController::class, 'enregistrer'])->name('produits.bundle.enregistrer');

    // ── Communauté : annonces + modération ──
    Route::get('produits/{produit}/communaute', [Admin\CommunauteController::class, 'gestion'])->name('produits.communaute.gestion');
    Route::post('produits/{produit}/communaute', [Admin\CommunauteController::class, 'poster'])->name('produits.communaute.poster');
    Route::delete('communaute/message/{message}', [Admin\CommunauteController::class, 'supprimer'])->name('produits.communaute.supprimer');

    // ── Licences : gestion des clés ──
    Route::get('produits/{produit}/licences', [Admin\LicenceController::class, 'gestion'])->name('produits.licences.gestion');
    Route::post('produits/{produit}/licences/ajouter', [Admin\LicenceController::class, 'ajouter'])->name('produits.licences.ajouter');
    Route::post('produits/{produit}/licences/generer', [Admin\LicenceController::class, 'generer'])->name('produits.licences.generer');
    Route::delete('licences/cle/{cle}', [Admin\LicenceController::class, 'supprimer'])->name('produits.licences.supprimer');

    Route::resource('produits', Admin\ProduitController::class);

    // ── Co-publication ────────────────────────────────────────────────
    Route::prefix('copublications')->name('copublications.')->group(function () {
        Route::get('/',                             [Admin\CopublicationController::class, 'index'])->name('index');
        Route::get('/inviter',                      [Admin\CopublicationController::class, 'create'])->name('create');
        Route::post('/',                            [Admin\CopublicationController::class, 'store'])->name('store');
        Route::post('/{copublication}/accepter',    [Admin\CopublicationController::class, 'accepter'])->name('accepter');
        Route::post('/{copublication}/refuser',     [Admin\CopublicationController::class, 'refuser'])->name('refuser');
        Route::delete('/{copublication}',           [Admin\CopublicationController::class, 'destroy'])->name('destroy');
        // ── Recherche IA de partenaires ─────────────────────────
        Route::get('/rechercher',                   [Admin\CopublicationController::class, 'rechercher'])->name('rechercher');
        Route::post('/ia-search',                   [Admin\CopublicationController::class, 'iaSearch'])->middleware('throttle:20,1')->name('ia-search');
    });

    // ── Upsells (imbriqués sous les produits) ─────────────────────────
    Route::prefix('produits/{produit}/upsells')->name('produits.upsells.')->group(function () {
        Route::get('/',                     [Admin\UpsellController::class, 'index'])->name('index');
        Route::get('/creer',                [Admin\UpsellController::class, 'create'])->name('create');
        Route::post('/',                    [Admin\UpsellController::class, 'store'])->name('store');
        Route::get('/{upsell}/modifier',    [Admin\UpsellController::class, 'edit'])->name('edit');
        Route::put('/{upsell}',             [Admin\UpsellController::class, 'update'])->name('update');
        Route::post('/{upsell}/toggle',     [Admin\UpsellController::class, 'toggleActif'])->name('toggle');
        Route::delete('/{upsell}',          [Admin\UpsellController::class, 'destroy'])->name('destroy');
    });

    // Gestion des catégories
    Route::resource('categories', Admin\CategorieController::class);

    // Gestion des clients
    Route::resource('clients', Admin\ClientController::class)->only(['index', 'show']);
    Route::get('clients/{client}/historique', [Admin\ClientController::class, 'historique'])
        ->name('clients.historique');

    // Gestion des transactions
    Route::resource('transactions', Admin\TransactionController::class)->only(['index', 'show']);
    Route::post('/transactions/sync-moneroo',      [Admin\TransactionController::class, 'syncMoneroo'])->name('transactions.sync-moneroo');
    Route::post('/transactions/{transaction}/sync', [Admin\TransactionController::class, 'syncUne'])->name('transactions.sync-une');

    // Gestion des codes promo
    Route::resource('codes-promo', Admin\CodePromoController::class);

    // Gestion des avis
    Route::resource('avis', Admin\AvisController::class)->only(['index', 'destroy']);
    Route::post('avis/{avis}/toggle-visibilite', [Admin\AvisController::class, 'toggleVisibilite'])
        ->name('avis.toggle-visibilite');

    // Gestion des pixels marketing
    Route::resource('pixels', Admin\PixelController::class);
    Route::post('pixels/{pixel}/toggle-activation', [Admin\PixelController::class, 'toggleActivation'])
        ->name('pixels.toggle-activation');

    // Configuration
    Route::prefix('configurations')->name('configurations.')->group(function () {
        Route::get('/', [Admin\ConfigurationController::class, 'index'])->name('index');
        Route::match(['get', 'post'], '/general', [Admin\ConfigurationController::class, 'general'])->name('general');
        Route::match(['get', 'post'], '/paiement', [Admin\ConfigurationController::class, 'paiement'])->name('paiement');
        Route::match(['get', 'post'], '/email', [Admin\ConfigurationController::class, 'email'])->name('email');
    });

    // Statistiques
    Route::prefix('statistiques')->name('statistiques.')->group(function () {
        Route::get('/ventes', [Admin\StatistiqueController::class, 'ventes'])->name('ventes');
        Route::get('/produits', [Admin\StatistiqueController::class, 'produits'])->name('produits');
    });

    // ── Exports CSV ────────────────────────────────────────────────────────────
    Route::prefix('exports')->name('exports.')->group(function () {
        Route::get('/produits',      [Admin\ExportController::class, 'produits'])->name('produits');
        Route::get('/clients',       [Admin\ExportController::class, 'clients'])->name('clients');
        Route::get('/transactions',  [Admin\ExportController::class, 'transactions'])->name('transactions');
    });

    // ── Configuration boutique avancée (thème, WhatsApp) ──────────────────────
    Route::match(['get','post'], '/configurations/apparence', [Admin\ConfigurationController::class, 'apparence'])
        ->name('configurations.apparence');

    // Profil
    Route::get('/profil', [Admin\ProfilController::class, 'index'])->name('profil');
    Route::prefix('profil')->name('profil.')->group(function () {
        Route::put('/update', [Admin\ProfilController::class, 'update'])->name('update');
        Route::put('/password', [Admin\ProfilController::class, 'password'])->name('password');
        Route::put('/avatar', [Admin\ProfilController::class, 'avatar'])->name('avatar');
        Route::put('/preferences', [Admin\ProfilController::class, 'preferences'])->name('preferences');
    });

    // ── Notifications in-app ──────────────────────────────────────────
    Route::prefix('notifications')->name('notifications.')->group(function () {
        Route::get('/',                               [Admin\NotificationController::class, 'index'])->name('index');
        Route::get('/recentes',                       [Admin\NotificationController::class, 'recentes'])->name('recentes');
        Route::post('/toutes-lues',                   [Admin\NotificationController::class, 'marquerToutesLues'])->name('toutes-lues');
        Route::post('/{notification}/lue',            [Admin\NotificationController::class, 'marquerLue'])->name('lue');
        Route::delete('/{notification}',              [Admin\NotificationController::class, 'destroy'])->name('destroy');
    });

    // ── KYC Marchand ──────────────────────────────────────────────────
    Route::prefix('kyc')->name('kyc.')->group(function () {
        Route::get('/',          [Admin\KycController::class, 'index'])->name('index');
        Route::post('/soumettre',[Admin\KycController::class, 'soumettre'])->name('soumettre');
    });

});