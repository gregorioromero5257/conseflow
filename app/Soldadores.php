<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Soldadores extends Model
{
    //
    protected $fillable = ['nombre', 'numero_serie_maquina','num'];
    protected $table = 'soldadores';
    public $timestamps = false;
}
