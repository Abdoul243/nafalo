<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->json('coaching_disponibilites')->nullable()->after('coaching_duree'); // {lundi:[{debut,fin}],...}
            $table->unsignedInteger('coaching_pause')->nullable()->after('coaching_disponibilites'); // minutes entre séances
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['coaching_disponibilites', 'coaching_pause']);
        });
    }
};
