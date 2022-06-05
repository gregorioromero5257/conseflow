<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasServicioUnidades extends Model
{
    //
    protected $fillable = ['catalogo_trafico_id','unidad_id','servicio_id'];
    protected $table = 'partidas_servicios_unidades';
    public $timestamps = false;

}
