<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BeneficiarioViaticoDBP extends Model
{
  protected $connection = 'csct';
  protected $fillable = ['solicitud_viaticos_id','empleado_beneficiario_id','datos_bancarios_empleado_id','cuenta','clabe','tarjeta','beneficiario_externo','banco_nombre'];
  // protected $fillable = ['solicitud_viaticos_id','empleado_beneficiario_id','datos_bancarios_empleado_id'];
  protected $table = 'beneficiario_viatico';
  public $timestamps = false;
}
