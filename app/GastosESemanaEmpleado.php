<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastosESemanaEmpleado extends Model
{
    //
    protected $fillable = ['empresa','empleado_id','nombre'];
    protected $table = 'gastos_e_semana_empleado';
    public $timestamps = false;
}
