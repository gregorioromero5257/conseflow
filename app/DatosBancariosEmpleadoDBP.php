<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosBancariosEmpleadoDBP extends Model
{
    protected $connection = 'csct'; 
    protected $fillable = ['numero_tarjeta','numero_cuenta','clabe','banco_id','empleado_id','condicion'];
    protected $table = 'datos_bancarios_empleados';
    public $timestamps = false;
}
