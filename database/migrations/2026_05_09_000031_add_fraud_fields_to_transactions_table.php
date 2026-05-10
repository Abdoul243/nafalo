<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            if (!Schema::hasColumn('transactions', 'est_suspicieux')) {
                $table->boolean('est_suspicieux')->default(false)->after('statut');
            }
            if (!Schema::hasColumn('transactions', 'raison_suspicion')) {
                $table->string('raison_suspicion')->nullable()->after('est_suspicieux');
            }
            if (!Schema::hasColumn('transactions', 'ip_client')) {
                $table->string('ip_client')->nullable()->after('raison_suspicion');
            }
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $cols = [];
            foreach (['est_suspicieux', 'raison_suspicion', 'ip_client'] as $col) {
                if (Schema::hasColumn('transactions', $col)) {
                    $cols[] = $col;
                }
            }
            if ($cols) $table->dropColumn($cols);
        });
    }
};
