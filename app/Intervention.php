<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Intervention extends Model
{
    // utilisation d'attribut identique aux noms de champs dans la base de données, model ruche lié à la table ruches
    protected $table = 'interventions';

    protected $fillable = [
        'id', 'texte', 'date_creation', 'idRuche',
    ];

    public $timestamps = false;
}
