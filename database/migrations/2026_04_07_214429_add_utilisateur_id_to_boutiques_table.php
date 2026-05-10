<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->unsignedBigInteger('utilisateur_id')->nullable()->after('id');
            $table->foreign('utilisateur_id')
                  ->references('id')
                  ->on('utilisateurs')
                  ->onDelete('cascade');
        });

        // Lier les boutiques existantes au premier utilisateur admin
        $admin = \App\Models\Utilisateur::where('role', 'admin')->first();
        if ($admin) {
            \App\Models\Boutique::whereNull('utilisateur_id')
                ->update(['utilisateur_id' => $admin->id]);
        }
    }

    public function down(): void
    {
        Schema::table('boutiques', function (Blueprint $table) {
            $table->dropForeign(['utilisateur_id']);
            $table->dropColumn('utilisateur_id');
        });
    }
};