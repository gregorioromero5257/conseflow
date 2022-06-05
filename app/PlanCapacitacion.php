<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PlanCapacitacion extends Model
{
    protected $fillable = ['empresa_id','nombre_curso','fecha_inicio','fecha_fin','duracion','nombre_capacitador','empresa_capacitadora','costo','id_capemp'];
    protected $table = 'capacitacion';
    public $timestamps = false;
}
