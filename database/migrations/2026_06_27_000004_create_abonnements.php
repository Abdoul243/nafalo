<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Type d'accès du produit : paiement unique ou abonnement récurrent
        Schema::table('produits', function (Blueprint $table) {
            $table->enum('acces_type', ['unique', 'abonnement'])->default('unique')->after('format');
            $table->enum('abonnement_intervalle', ['mensuel', 'annuel'])->nullable()->after('acces_type');
        });

        // Abonnements clients (renouvellement manuel)
        Schema::create('abonnements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained('boutiques')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->enum('statut', ['actif', 'expire', 'annule'])->default('actif');
            $table->enum('intervalle', ['mensuel', 'annuel'])->default('mensuel');
            $table->decimal('prix', 10, 2)->default(0);
            $table->timestamp('date_debut')->nullable();
            $table->timestamp('date_fin')->nullable();
            $table->boolean('rappel_envoye')->default(false); // rappel d'échéance
            $table->unsignedBigInteger('derniere_transaction_id')->nullable();
            $table->timestamps();

            $table->index(['client_id', 'produit_id']);
            $table->index(['statut', 'date_fin']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('abonnements');
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['acces_type', 'abonnement_intervalle']);
        });
    }
};
