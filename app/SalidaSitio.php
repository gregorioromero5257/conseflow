<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalidaSitio extends Model
{
    //
      protected $fillable = ['fecha','folio','format_salida','descripcion','ubicacion','proyecto_id','tiposalida_id','empleado_solicita_id','empleado_entrega_id','empleado_autoriza_id','empleado_recibe_id','condicion','supervisores_proyectos_id'];
      protected $table = 'salidassitio';
      public $timestamps = false;
}
