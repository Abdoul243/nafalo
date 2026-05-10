<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\BoutiqueMiddleware;
use App\Http\Middleware\ClientAuthMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'domaine' => BoutiqueMiddleware::class,
            'client.auth' => ClientAuthMiddleware::class,
            'superadmin' => App\Http\Middleware\SuperAdminMiddleware::class,
        ]);

        // Exclure le webhook GeniusPay de la vérification CSRF
        $middleware->validateCsrfTokens(except: [
            'boutique/checkout/webhook/geniuspay',
        ]);

        // Forcer le schéma HTTPS pour les URLs générées par Laravel
        $middleware->trustProxies(at: '*');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();