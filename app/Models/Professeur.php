<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Professeur extends Model
{
    /** @use HasFactory<\Database\Factories\ProfesseurFactory> */
    use HasFactory;

    public function modules(){
        return $this->belongsTo(Module::class);
    }
}
