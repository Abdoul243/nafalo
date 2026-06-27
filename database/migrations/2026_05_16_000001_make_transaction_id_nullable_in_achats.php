<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Supprimer la contrainte de clé étrangère existante puis la recréer nullable
        // (requis pour les achats lead magnet qui n'ont pas de transaction)
        Schema::table('achats', function (Blueprint $table) {
            // Supprimer la contrainte FK d'abord
            $table->dropForeign(['transaction_id']);
        });

        Schema::table('achats', function (Blueprint $table) {
            // Rendre nullable + recréer la FK
            $table->unsignedBigInteger('transaction_id')->nullable()->change();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        // Remettre NOT NULL (attention : échouera s'il y a des lignes avec NULL)
        Schema::table('achats', function (Blueprint $table) {
            $table->dropForeign(['transaction_id']);
        });
        Schema::table('achats', function (Blueprint $table) {
            $table->unsignedBigInteger('transaction_id')->nullable(false)->change();
            $table->foreign('transaction_id')->references('id')->on('transactions')->onDelete('cascade');
        });
    }
};
