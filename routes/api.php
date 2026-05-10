<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api;

// Routes API pour les webhooks (publiques)
Route::post('/paiement/stripe/webhook', [Boutique\PaiementController::class, 'webhook']);
Route::post('/paiement/paypal/webhook', [Boutique\PaiementController::class, 'webhook']);

// Routes API protégées
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Statistiques
    Route::get('/statistiques/ventes', [Api\StatistiqueController::class, 'ventes']);
    Route::get('/statistiques/produits', [Api\StatistiqueController::class, 'produits']);
    
    // Produits
    Route::apiResource('produits', Api\ProduitController::class);
    
    // Transactions
    Route::apiResource('transactions', Api\TransactionController::class)->only(['index', 'show']);
});