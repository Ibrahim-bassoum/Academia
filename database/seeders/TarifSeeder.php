<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Filiere;
use App\Models\Tarif;
use App\Models\Niveau;


class TarifSeeder extends Seeder
{
 public function run(): void
{
    // Exemple si tu as une table Niveaux avec les noms 'L1', 'L2', 'L3', 'M1', 'M2'
   // Au lieu de chercher par 'code', cherche uniquement par le 'nom'
$l1 = Niveau::where('nom', 'LIKE', '%Licence 1%')->orWhere('nom', 'LIKE', '%L1%')->first();
$l2 = Niveau::where('nom', 'LIKE', '%Licence 2%')->orWhere('nom', 'LIKE', '%L2%')->first();
$l3 = Niveau::where('nom', 'LIKE', '%Licence 3%')->orWhere('nom', 'LIKE', '%L3%')->first();

    if ($l1) {
        Tarif::updateOrCreate(['niveau_id' => $l1->id], ['frais_inscription' => 50000, 'montant_scolarite' => 350000]);
    }
    if ($l2) {
        Tarif::updateOrCreate(['niveau_id' => $l2->id], ['frais_inscription' => 50000, 'montant_scolarite' => 400000]);
    }
    if ($l3) {
        Tarif::updateOrCreate(['niveau_id' => $l3->id], ['frais_inscription' => 50000, 'montant_scolarite' => 450000]);
    }
}
}