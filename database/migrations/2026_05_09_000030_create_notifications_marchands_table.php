<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications_marchands', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->string('type', 60); // vente, avis, lead, copub_invitation, copub_reponse
            $table->string('titre');
            $table->text('message');
            $table->string('lien')->nullable();
            $table->json('data')->nullable();
            $table->timestamp('lu_le')->nullable();
            $table->timestamps();

            $table->index(['utilisateur_id', 'lu_le']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications_marchands');
    }
};
