<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidaServicioTrafico extends Model
{
    protected $fillable = ['id', 'tipo_servicio_id', 'descripcion', 'precio', 'servicio_trafico_id'];
    protected $table = 'partidas_servicios_trafico';
    public $timestamps = false;
}
