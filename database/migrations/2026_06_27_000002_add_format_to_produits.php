<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Format de livraison : fichier téléchargeable OU formation (espace membre)
            $table->enum('format', ['fichier', 'formation'])->default('fichier')->after('type');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('format');
        });
    }
};
