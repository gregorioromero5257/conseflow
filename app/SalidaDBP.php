<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalidaDBP extends Model
{
    //
      protected $connection = 'csct';
      protected $fillable = ['fecha','folio','format_salida','descripcion','ubicacion','proyecto_id','tiposalida_id','empleado_id','condicion','supervisores_proyectos_id'];
      protected $table = 'salidas';
      public $timestamps = false;
}
