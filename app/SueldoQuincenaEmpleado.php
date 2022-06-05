<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoQuincenaEmpleado extends Model
{
    //
    protected $fillable = ['empresa','empleado_id','nombre','aplica'];
    protected $table = 'sueldo_quincena_empleado';
    public $timestamps = false;
}
