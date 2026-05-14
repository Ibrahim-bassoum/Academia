<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Filiere extends Model
{
    /** @use HasFactory<\Database\Factories\FiliereFactory> */
    use HasFactory;
    public function niveaux(){
        return $this->hasMany(Niveau::class);
    }

    public function etudiants(){
        return $this->hasMany(Etudiant::class);
    }

    public function modules(){
        return$this->belongsTo(Module::class);
    }
}

