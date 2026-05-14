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
      Schema::create('suivi_presences', function (Blueprint $table) {
    $table->id();
    $table->foreignId('etudiant_id')->constrained()->onDelete('cascade');
    $table->foreignId('module_id')->constrained()->onDelete('cascade');
    $table->date('date_cours');
    $table->enum('statut', ['present', 'absent', 'retard'])->default('present');
    $table->boolean('est_justifie')->default(false);
    $table->string('commentaire')->nullable();  
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suivis');
    }
};
