<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientoDBP extends Model
{
    //
      protected $connection = 'csct';
      protected $fillable = ['cantidad','fecha','hora','tipo_movimiento','folio','proyecto_id','lote_id','stocke_id','almacene_id','stand_id','nivel_id','articulo_id'];
      protected $table = 'movimientos';
      public $timestamps = false;
}
