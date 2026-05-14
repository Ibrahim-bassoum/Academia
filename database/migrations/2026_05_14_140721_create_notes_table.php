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
       Schema::create('notes', function (Blueprint $table) {
    $table->id();
    // Clés étrangères
    $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
    $table->foreignId('module_id')->constrained()->onDelete('cascade');
    
    // Données de la note
    $table->decimal('valeur', 5, 2); // ex: 18.75
    $table->enum('type', ['devoir', 'examen', 'rattrapage']); 
    $table->integer('coefficient')->default(1);
    
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
