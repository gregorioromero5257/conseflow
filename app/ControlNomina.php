<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlNomina extends Model
{
    protected $fillable = ['empleado_id','proyecto_id','nomMov_id','contrato_id','sueldo','fecha'];
    protected $table = 'control_nomina';
    public $timestamps = false;
}
