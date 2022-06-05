<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SueldoDBP extends Model
{
    //
      protected $connection = 'csct';
      protected $fillable = ['sueldo_diario_integral','sueldo_mensual','infonavit','viaticos_mensuales','sueldo_diario_neto','contrato_id','sueldo_diario_real','fecha_act'];
      protected $table = 'sueldos';
      public $timestamps = false;
}
