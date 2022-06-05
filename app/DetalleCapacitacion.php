<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleCapacitacion extends Model
{
    protected $fillable = ['empleado_id','documento'];
    protected $table = 'capacitacion_empleado';
    public $timestamps = false;
}
