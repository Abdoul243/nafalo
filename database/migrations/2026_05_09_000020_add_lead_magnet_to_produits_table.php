<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            // Type du produit : payant ou gratuit (lead magnet)
            $table->enum('type', ['payant', 'gratuit'])
                  ->default('payant')
                  ->after('est_publie');

            // Champs que le marchand veut collecter (JSON array)
            // Valeurs possibles : 'telephone', 'ville', 'profession', 'pays'
            // Nom + Email sont toujours obligatoires
            $table->json('lead_champs_requis')
                  ->nullable()
                  ->after('type');

            // Limite de téléchargements (null = illimité)
            $table->unsignedInteger('lead_limite_dl')
                  ->nullable()
                  ->after('lead_champs_requis');

            // Compteur de téléchargements gratuits effectués
            $table->unsignedInteger('lead_compteur')
                  ->default(0)
                  ->after('lead_limite_dl');
        });
    }

    public function down(): void
    {
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn(['type', 'lead_champs_requis', 'lead_limite_dl', 'lead_compteur']);
        });
    }
};
