<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Traspaso extends Model
{
    //
      protected $fillable = ['fecha_salida','estado','empleado_transporta_id','stock_id','comentarios','tipo_ubicacion_id','origen_id'];
      protected $table = 'traspasos';
      public $timestamps = false;
}
