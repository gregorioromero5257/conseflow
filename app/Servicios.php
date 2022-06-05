<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Servicios extends Model
{
    //
    protected $fillable = [
      'nombre_servicio',
      'proveedor_marca',
      'unidad_medida',
      'centro_costos_id',
    ];
    protected $table = 'servicios';
    public $timestamps = false;
}
