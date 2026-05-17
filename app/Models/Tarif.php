<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tarif extends Model
{
    use HasFactory;

    protected $fillable = [
        'niveau_id',
        'frais_inscription',
        'montant_scolarite',
    ];

    // Un tarif est lié à une filière spécifique
    public function filiere()
    {
        return $this->belongsTo(Filiere::class);
    }

     public function niveau()
    {
        return $this->belongsTo(Filiere::class);
    }

}