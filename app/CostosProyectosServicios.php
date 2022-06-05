<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostosProyectosServicios extends Model
{
    //
    protected $fillable = ['catalogo_centro_costos_id','requisicion_has_ordencompra_id'];
    protected $table = 'costos_proyectos_servicios';
    public $timestamps = false;
}
