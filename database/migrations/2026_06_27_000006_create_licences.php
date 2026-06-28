<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Ajoute "licence" aux formats de produit possibles
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence') NOT NULL DEFAULT 'fichier'");

        // Pool de clés de licence par produit
        Schema::create('cles_licence', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->string('cle');
            $table->enum('statut', ['disponible', 'attribuee'])->default('disponible');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->foreignId('achat_id')->nullable()->constrained('achats')->onDelete('set null');
            $table->timestamp('attribuee_at')->nullable();
            $table->timestamps();

            $table->unique(['produit_id', 'cle']);
            $table->index(['produit_id', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cles_licence');
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation') NOT NULL DEFAULT 'fichier'");
    }
};
