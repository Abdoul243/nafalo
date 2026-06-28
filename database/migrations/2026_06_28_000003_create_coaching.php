<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence','bundle','communaute','coaching') NOT NULL DEFAULT 'fichier'");

        Schema::table('produits', function (Blueprint $table) {
            $table->unsignedInteger('coaching_duree')->nullable()->after('abonnement_intervalle'); // minutes
        });

        Schema::create('reservations_coaching', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('achat_id')->nullable()->constrained('achats')->onDelete('set null');
            $table->dateTime('date_souhaitee');
            $table->dateTime('date_confirmee')->nullable();
            $table->enum('statut', ['en_attente', 'confirmee', 'annulee'])->default('en_attente');
            $table->string('lien_visio')->nullable();
            $table->text('message')->nullable();
            $table->timestamps();

            $table->index(['produit_id', 'statut']);
            $table->index(['client_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations_coaching');
        Schema::table('produits', function (Blueprint $table) {
            $table->dropColumn('coaching_duree');
        });
        DB::statement("ALTER TABLE produits MODIFY format ENUM('fichier','formation','licence','bundle','communaute') NOT NULL DEFAULT 'fichier'");
    }
};
