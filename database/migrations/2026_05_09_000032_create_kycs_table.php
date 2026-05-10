<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('kycs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('utilisateur_id')->constrained('utilisateurs')->onDelete('cascade');
            $table->enum('statut', ['non_soumis', 'en_attente', 'approuve', 'rejete'])->default('non_soumis');
            $table->enum('type_document', ['cni', 'passeport', 'permis'])->nullable();
            $table->string('document_recto')->nullable();   // storage path
            $table->string('document_verso')->nullable();   // storage path (optional)
            $table->text('note_admin')->nullable();
            $table->timestamp('soumis_le')->nullable();
            $table->timestamp('traite_le')->nullable();
            $table->foreignId('traite_par')->nullable()->constrained('utilisateurs')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kycs');
    }
};
