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
        Schema::create('paniers_abandonnes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained()->onDelete('set null');
            $table->string('email', 191)->nullable();
            $table->json('contenu');
            $table->decimal('montant_total', 10, 2)->nullable();
            $table->boolean('relance_envoyee')->default(false);
            $table->timestamp('date_relance')->nullable();
            $table->timestamps();
            
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paniers_abandonnes');
    }
};