<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Salida extends Model
{
    //
      protected $fillable = ['fecha','folio','format_salida','descripcion','ubicacion','proyecto_id','tiposalida_id','empleado_id','condicion','supervisores_proyectos_id'];
      protected $table = 'salidas';
      public $timestamps = false;
}
