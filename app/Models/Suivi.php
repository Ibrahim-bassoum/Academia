<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suivi extends Model
{
    /** @use HasFactory<\Database\Factories\SuiviFactory> */
    use HasFactory;
        
    protected $table ='suivi_presences';
    protected $fillable = ['etudiant_id', 'module_id', 'date_cours', 'statut', 'est_justifie', 'commentaire'];

    public function etudiant() {
        return $this->belongsTo(Etudiant::class);
    }

    public function module() {
        return $this->belongsTo(Module::class);
    }
}
