<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoServicioTrafico extends Model
{
    protected $fillable = ['id', 'nombre'];
    protected $table = 'tipo_servicio_trafico';
    public $timestamps = false;
}
