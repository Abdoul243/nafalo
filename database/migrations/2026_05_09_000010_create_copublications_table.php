<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('copublications', function (Blueprint $table) {
            $table->id();

            // Produit concerné
            $table->foreignId('produit_id')
                  ->constrained('produits')
                  ->onDelete('cascade');

            // Marchand propriétaire (celui qui initie la co-publication)
            $table->foreignId('proprietaire_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            // Marchand co-publicateur invité
            $table->foreignId('copublicateur_id')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');

            // Boutique du co-publicateur (pour lui attribuer les ventes)
            $table->foreignId('boutique_copublicateur_id')
                  ->constrained('boutiques')
                  ->onDelete('cascade');

            // Pourcentages (doivent totaliser 100 %)
            $table->decimal('pourcentage_proprietaire', 5, 2)->default(70.00);
            $table->decimal('pourcentage_copublicateur', 5, 2)->default(30.00);

            // Statut de l'invitation
            $table->enum('statut', ['en_attente', 'accepte', 'refuse'])->default('en_attente');

            // Message optionnel du propriétaire
            $table->text('message')->nullable();

            $table->timestamps();

            // Un seul accord actif par (produit, copublicateur)
            $table->unique(['produit_id', 'copublicateur_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('copublications');
    }
};
