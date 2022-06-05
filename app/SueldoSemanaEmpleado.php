<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoSemanaEmpleado extends Model
{
    //
    protected $fillable = ['empresa','empleado_id','nombre','aplica'];
    protected $table = 'sueldo_semana_empleado';
    public $timestamps = false;
}
