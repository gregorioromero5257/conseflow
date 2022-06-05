<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DireccionEmpleado extends Model
{
  protected $fillable = ['codigo_postal','numero_exterior','numero_interior','localidad','entidad_federativa','calle','entre_que_calles','tipo_avenida','colonia','municipio','condicion','empleado_id'];
  protected $table = 'direcciones_empleados';
  public $timestamps = false;
}
