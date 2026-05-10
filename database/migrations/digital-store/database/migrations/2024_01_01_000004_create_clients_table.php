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
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('boutique_id')->constrained()->onDelete('cascade');
            $table->string('email', 191);
            $table->string('nom', 191)->nullable();
            $table->string('telephone')->nullable();
            $table->string('code_acces', 6)->nullable();
            $table->timestamp('code_expire_at')->nullable();
            $table->timestamps();
            
            $table->unique(['boutique_id', 'email']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clients');
    }
};