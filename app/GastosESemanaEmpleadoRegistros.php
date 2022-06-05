<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastosESemanaEmpleadoRegistros extends Model
{
    //7
    protected $fillable = ['gasto_e_semana_empleado_id','semana','fecha_i','fecha_f','total'];
    protected $table = 'gastos_e_semana_empleado_registros';
    public $timestamps = false;
}
