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
          Schema::create('depenses', function (Blueprint $table) {
        $table->id();
        $table->string('libelle'); // Ex: Achat craies, Salaire vigile
        $table->decimal('montant', 12, 2); // 12 chiffres pour être large avec le FCFA
        $table->string('categorie'); // Salaire, Matériel, Loyer, etc.
        $table->date('date_depense');
        $table->text('description')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('depenses');
    }
};
