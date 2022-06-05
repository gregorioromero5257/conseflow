<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastosEQuincenalEmpleadoRegistros extends Model
{
    //7
    protected $fillable = ['gastos_e_quincenal_empleado_id','semana','fecha_i','fecha_f','total'];
    protected $table = 'gastos_e_quincenal_empleado_registros';
    public $timestamps = false;
}
