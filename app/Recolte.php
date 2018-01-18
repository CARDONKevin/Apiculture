<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recolte extends Model
{
    protected $table = 'recoltes';

    protected $fillable = [
        'id', 'date', 'poids', 'idRuche',
    ];

    public $timestamps = false;
}
