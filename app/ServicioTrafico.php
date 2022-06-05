<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioTrafico extends Model
{
    protected $fillable = ['id', 'proveedor', 'fecha', 'total', 'chofer_id', 'unidad_id'];
    protected $table = 'servicios_trafico';
    public $timestamps = false;
}
