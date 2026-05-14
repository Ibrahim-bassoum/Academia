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
       Schema::create('professeurs', function (Blueprint $table) {
    $table->id();
    $table->string('matricule')->unique(); // ex: PROF-2026-001
    $table->string('nom');
    $table->string('prenom');
    $table->string('specialite')->nullable(); // ex: Réseaux, Développement Web
    $table->string('telephone')->nullable();
    
    // Relation avec la table users pour la connexion au Dashboard
    $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
    
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('professeurs');
    }
};
