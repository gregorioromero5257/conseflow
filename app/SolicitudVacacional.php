<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudVacacional extends Model
{

    protected $fillable = ['fecha_solicitud', 'formato_vacacion', 'fecha_inicio', 'fecha_fin', 'total_dias', 'estado', 'autoriza_empleado_id', 'solicita_empleado_id'];
    protected $table = 'solicitudes_vacacionales';
    public $timestamps = false;
}
