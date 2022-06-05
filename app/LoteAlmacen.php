<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteAlmacen extends Model
{
    //
      protected $fillable = ['lote_id','almacene_id','nivel_id','stand_id','cantidad','stocke_id','articulo_id'];
      protected $table = 'lote_almacen';
      public $timestamps = false;
}
