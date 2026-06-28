<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence','bundle','communaute') NOT NULL DEFAULT 'fichier'");

        Schema::create('messages_communaute', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clients')->onDelete('set null');
            $table->boolean('est_marchand')->default(false); // publié par le propriétaire
            $table->string('nom_auteur');
            $table->text('contenu');
            $table->timestamps();

            $table->index(['produit_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages_communaute');
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence','bundle') NOT NULL DEFAULT 'fichier'");
    }
};
