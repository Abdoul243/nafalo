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
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
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
        Schema::defaultStringLength(191);

        Gate::policy(Categorie::class, CategoriePolicy::class);
        Gate::policy(Client::class, ClientPolicy::class);
        Gate::policy(Produit::class, ProduitPolicy::class);
    }
}
