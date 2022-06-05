<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CentroCostos extends Model
{
    //
    protected $fillable = ['catalogo_centro_costos_id','partida_costos_id','proyecto_id'];
    protected $table = "centro_costos";
    public $timestamps = false;
}
