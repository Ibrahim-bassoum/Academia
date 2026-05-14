<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Niveau extends Model
{
    /** @use HasFactory<\Database\Factories\NiveauFactory> */
    use HasFactory;
    public function filieres(){
        return $this->belongsTo(Filiere::class);
    }
    public function modules(){
        return $this->hasMany(Module::class);
    }
    public function etudiants(){
        return$this->hasMany(Etudiant::class);
    }
}
