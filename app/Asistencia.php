<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    //
      protected $fillable = ['fecha','horas_trabajadas','dia_id','registro_id','empleado_id','contrato_id','nombre'];
      protected $table = 'asistencias';
      public $timestamps = false;
}
