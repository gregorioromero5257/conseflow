<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrada extends Model
{
    //
      protected $fillable = ['fecha','comentarios','formato_entrada','tipo_adq_id','tipo_entrada_id','empleado_calidad_id','empleado_almacen_id','empleado_usuario_id','condicion','orden_compra_id','estado'];
      protected $table = 'entradas';
      public $timestamps = false;
}
