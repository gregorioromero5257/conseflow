<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlTiempoIncidencias extends Model
{
    //
    protected $fillable = ['supervisor_id', 'empleado_asignado_id', 'fecha', 'proyecto_id'];
    protected $table = 'incidencias_control_tiempo';
    public $timestamps = false;
}
