<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasTraspasos extends Model
{
    //
      protected $fillable = ['estado','traspaso_id','lote_almacen_id','cantidad','tipo_calidad_id'];
      protected $table = 'partidas_traspasos';
      public $timestamps = false;
}
