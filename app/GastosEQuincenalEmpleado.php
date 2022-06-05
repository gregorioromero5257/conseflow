<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastosEQuincenalEmpleado extends Model
{
    //
    protected $fillable = ['empresa','empleado_id','nombre'];
    protected $table = 'gastos_e_quincenal_empleado';
    public $timestamps = false;
}
