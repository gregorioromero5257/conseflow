<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasMantenimientoUnidades extends Model
{
    //
    protected $fillable = ['catalogo_trafico_id','unidad_id','mantenimiento_id'];
    protected $table = 'partidas_mantenimiento_unidades';
    public $timestamps = false;

}
