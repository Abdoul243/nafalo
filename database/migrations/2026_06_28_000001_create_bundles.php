<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajoute "bundle" aux formats de produit
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence','bundle') NOT NULL DEFAULT 'fichier'");

        // Produits inclus dans un bundle (pack)
        Schema::create('bundle_produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bundle_id')->constrained('produits')->onDelete('cascade');   // le produit "pack"
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');   // un produit inclus
            $table->timestamps();

            $table->unique(['bundle_id', 'produit_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bundle_produits');
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence') NOT NULL DEFAULT 'fichier'");
    }
};
