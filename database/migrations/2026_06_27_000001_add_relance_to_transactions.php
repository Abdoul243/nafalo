<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Relance email pour paniers abandonnés / paiements non finalisés
            $table->boolean('relance_envoyee')->default(false)->after('statut');
            $table->timestamp('relance_envoyee_at')->nullable()->after('relance_envoyee');
            $table->index(['statut', 'relance_envoyee', 'created_at'], 'idx_relance');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropIndex('idx_relance');
            $table->dropColumn(['relance_envoyee', 'relance_envoyee_at']);
        });
    }
};
