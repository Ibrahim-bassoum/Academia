<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Etudiant extends Model
{
    /** @use HasFactory<\Database\Factories\EtudiantFactory> */
    use HasFactory;
    public function niveau(){
        return $this->belongsTo(Niveau::class);
    }

    public function paiements(){
        return $this->hasMany(Paiement::class);

    }

    public function notes(){
        return $this->hasMany(Note::class);
    }

    public function suivis(){
        return $this->hasMany(Suivi::class);
    }
}
