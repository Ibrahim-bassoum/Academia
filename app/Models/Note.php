<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    /** @use HasFactory<\Database\Factories\NoteFactory> */
    use HasFactory;
      protected $fillable = ['etudiant_id', 'matiere_id', 'valeur', 'type', 'coefficient'];

    public function etudiant() {
        return $this->belongsTo(Etudiant::class);
    }

    public function matiere() {
        return $this->belongsTo(Module::class);
    }
}
