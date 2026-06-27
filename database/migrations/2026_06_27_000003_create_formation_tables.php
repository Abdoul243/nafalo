<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Modules d'une formation
        Schema::create('modules_formation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->string('titre');
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
            $table->index(['produit_id', 'ordre']);
        });

        // Leçons d'un module
        Schema::create('lecons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules_formation')->onDelete('cascade');
            $table->string('titre');
            $table->text('contenu')->nullable();          // texte / description riche
            $table->string('video_url')->nullable();       // lien YouTube / Vimeo (embed)
            $table->string('video_fichier')->nullable();   // vidéo uploadée (disque privé)
            $table->string('ressource_fichier')->nullable(); // ressource téléchargeable (privé)
            $table->unsignedInteger('duree')->nullable();  // durée en minutes
            $table->boolean('est_apercu')->default(false); // leçon visible en aperçu (sans achat)
            $table->unsignedInteger('ordre')->default(0);
            $table->timestamps();
            $table->index(['module_id', 'ordre']);
        });

        // Progression d'un client sur les leçons
        Schema::create('progressions_lecon', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('lecon_id')->constrained('lecons')->onDelete('cascade');
            $table->boolean('terminee')->default(false);
            $table->timestamp('terminee_at')->nullable();
            $table->timestamps();
            $table->unique(['client_id', 'lecon_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progressions_lecon');
        Schema::dropIfExists('lecons');
        Schema::dropIfExists('modules_formation');
    }
};
