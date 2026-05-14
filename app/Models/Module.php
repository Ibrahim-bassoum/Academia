<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    /** @use HasFactory<\Database\Factories\ModuleFactory> */
    use HasFactory;
    public function niveaux(){
        return $this->belongsTo(Niveau::class);
    }

    public function professeur(){
        return $this->belongsToMany(Professeur::class);
    }

    public function suivi(){
        return $this->hasMany(Suivi::class);
    }

    public function notes(){
        return $this->hasMany(Note::class);
    }
}
