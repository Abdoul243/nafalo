<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Le fichier devient optionnel : une formation (espace membre) n'a pas
        // de fichier téléchargeable. Évite l'erreur "Field 'fichier' doesn't
        // have a default value" lors de la création d'une formation.
        DB::statement("ALTER TABLE produits MODIFY fichier VARCHAR(255) NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE produits MODIFY fichier VARCHAR(255) NOT NULL");
    }
};
