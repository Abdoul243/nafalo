<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('upsells', function (Blueprint $table) {
            $table->id();

            // Produit principal (celui qui déclenche l'upsell)
            $table->foreignId('produit_id')
                  ->constrained('produits')
                  ->onDelete('cascade');

            // Produit proposé en upsell
            $table->foreignId('produit_upsell_id')
                  ->constrained('produits')
                  ->onDelete('cascade');

            // Texte de l'offre affiché au client
            $table->string('titre_offre')->default('🔥 Offre exclusive pour vous !');
            $table->text('description_offre')->nullable();

            // Prix spécial (optionnel — null = prix normal du produit upsell)
            $table->decimal('prix_special', 10, 2)->nullable();

            // Ordre d'affichage
            $table->integer('ordre')->default(0);

            $table->boolean('est_actif')->default(true);

            $table->timestamps();

            // Pas de doublon : un même upsell par paire de produits
            $table->unique(['produit_id', 'produit_upsell_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('upsells');
    }
};
