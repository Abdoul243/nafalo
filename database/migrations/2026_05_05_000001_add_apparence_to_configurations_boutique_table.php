<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('configurations_boutique', function (Blueprint $table) {
            $table->string('theme', 20)->default('light')->nullable()->after('devise');
            $table->string('couleur', 7)->default('#2563eb')->nullable()->after('theme');
            $table->string('langue', 5)->default('fr')->nullable()->after('couleur');
        });
    }

    public function down(): void
    {
        Schema::table('configurations_boutique', function (Blueprint $table) {
            $table->dropColumn(['theme', 'couleur', 'langue']);
        });
    }
};
