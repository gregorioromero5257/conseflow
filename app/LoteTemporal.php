<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteTemporal extends Model
{
    //
      protected $fillable = ['requisicion_id','articulo_id','lote_almacen_id','cantidad','comentario'];
      protected $table = 'lote_temporal';
      public $timestamps = false;
}
