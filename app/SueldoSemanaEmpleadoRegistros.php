<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoSemanaEmpleadoRegistros extends Model
{
    //7
    protected $fillable = ['sueldo_semana_empleado_id','semana','fecha_i','fecha_f','total','anio'];
    protected $table = 'sueldo_semana_empleado_registros';
    public $timestamps = false;
}
