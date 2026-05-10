<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('categorie_id')->nullable()->constrained()->onDelete('set null');
            $table->string('nom');
            $table->string('slug', 191);
            $table->text('description')->nullable();
            $table->decimal('prix', 10, 2);
            $table->string('image', 191)->nullable();
            $table->string('fichier', 191);
            $table->boolean('est_publie')->default(false);
            $table->integer('nb_ventes')->default(0);
            $table->timestamps();
            
            $table->unique(['boutique_id', 'slug']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};