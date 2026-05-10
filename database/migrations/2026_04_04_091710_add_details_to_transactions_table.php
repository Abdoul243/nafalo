<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'details')) {
                $table->json('details')->nullable()->after('montant_total');
            }
            if (!Schema::hasColumn('transactions', 'reference_paiement')) {
                $table->string('reference_paiement')->nullable()->after('statut');
            }
            if (!Schema::hasColumn('transactions', 'moyen_paiement')) {
                $table->string('moyen_paiement')->nullable()->after('reference_paiement');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['details', 'reference_paiement', 'moyen_paiement']);
        });
    }
};