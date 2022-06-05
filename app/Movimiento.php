<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Movimiento extends Model
{
    //
      protected $fillable = ['cantidad','fecha','hora','tipo_movimiento','folio','proyecto_id','lote_id','stocke_id','almacene_id','stand_id','nivel_id','articulo_id'];
      protected $table = 'movimientos';
      public $timestamps = false;
}
