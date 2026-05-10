<?php

namespace App\Providers;

use App\Models\Categorie;
use App\Models\Client;
use App\Models\Produit;
use App\Policies\CategoriePolicy;
use App\Policies\ClientPolicy;
use App\Policies\ProduitPolicy;
use App\Services\Paiement\LocalPaiementService;
use App\Services\Paiement\PaiementServiceInterface;
use App\Services\Paiement\PayPalService;
use App\Services\Paiement\StripeService;
use App\Support\WindowsFilesystem;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Corriger le bug rename() de Windows lors de la recompilation des vues Blade
        if (PHP_OS_FAMILY === 'Windows') {
            $this->app->singleton('files', fn () => new WindowsFilesystem());
        }

        $this->app->bind(PaiementServiceInterface::class, function () {
            $gateway = env('PAYMENT_GATEWAY', 'stripe');

            if ($gateway === 'paypal') {
                return class_exists(\PayPalCheckoutSdk\Core\PayPalHttpClient::class)
                    ? new PayPalService()
                    : new LocalPaiementService();
            }

            return class_exists(\Stripe\Stripe::class)
                ? new StripeService()
                : new LocalPaiementService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Forcer HTTPS pour toutes les URLs générées
        if (config('app.env') !== 'local' || str_starts_with(config('app.url'), 'https')) {
            URL::forceScheme('https');
        }

        Schema::defaultStringLength(191);

        Gate::policy(Categorie::class, CategoriePolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Produit::class, ProduitPolicy::class);

    }
}
