<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('configurations_boutique', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->unique()->constrained()->onDelete('cascade');
            $table->string('email_expediteur', 191)->nullable();
            $table->text('email_template_achat')->nullable();
            $table->text('email_template_relance')->nullable();
            $table->integer('relance_delai_jours')->default(3);
            $table->string('cle_api_paiement', 191)->nullable();
            $table->string('secret_api_paiement', 191)->nullable();
            $table->string('passerelle_paiement')->nullable();
            $table->string('devise', 10)->default('EUR');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configurations_boutique');
    }
};