<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('paiements', function (Blueprint $table) {
        // On ajoute le type : soit 'inscription', soit 'scolarite'
        $table->string('type')->default('scolarite')->after('montant');
    });
}

public function down(): void
{
    Schema::table('paiements', function (Blueprint $table) {
        $table->dropColumn('type');
    });
}
};
