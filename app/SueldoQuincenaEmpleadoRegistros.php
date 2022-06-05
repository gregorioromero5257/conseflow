<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoQuincenaEmpleadoRegistros extends Model
{
    //7
    protected $fillable = ['sueldo_quincena_empleado_id','semana','fecha_i','fecha_f','total','anio'];
    protected $table = 'sueldo_quincena_empleado_registros';
    public $timestamps = false;
}
