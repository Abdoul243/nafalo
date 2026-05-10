<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pages_ia', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->foreignId('boutique_id')->constrained('boutiques')->onDelete('cascade');
            $table->text('prompt_original');
            $table->longText('contenu_html');
            $table->string('slug_page')->nullable();
            $table->boolean('est_publiee')->default(false);
            $table->string('modele_ia')->default('claude-opus-4-5');
            $table->integer('tokens_utilises')->nullable();
            $table->timestamps();

            $table->index(['produit_id', 'est_publiee']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages_ia');
    }
};
