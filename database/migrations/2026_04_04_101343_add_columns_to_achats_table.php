<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->unsignedBigInteger('boutique_id')->nullable()->after('produit_id');
            $table->decimal('montant', 10, 2)->nullable()->after('boutique_id');
            $table->string('token_telechargement', 60)->nullable()->after('montant');
        });
    }

    public function down(): void
    {
        Schema::table('achats', function (Blueprint $table) {
            $table->dropColumn(['boutique_id', 'montant', 'token_telechargement']);
        });
    }
};