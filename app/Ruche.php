<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ruche extends Model
{
    // utilisation d'attribut identique aux noms de champs dans la base de données, model ruche lié à la table ruches
    protected $table = 'ruches';

    public $timestamps = false;
}
