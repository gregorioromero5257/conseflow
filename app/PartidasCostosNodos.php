<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasCostosNodos extends Model
{
    //
    protected $fillable = ['codigo','padre_id','unidad','cantidad','p_suministro','p_instalacion','p_unitario','importe','proyecto_id','ultimo','concepto'];
    protected $table = 'partidas_costos_nodos';
    public $timestamps;
}
