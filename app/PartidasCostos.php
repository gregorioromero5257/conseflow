<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasCostos extends Model
{
      protected $fillable = ['nombre','folio','monto_ejecutado','monto_cotizado','tipo_partida_id','proyecto_id','asignado','moneda'];
      protected $table = 'partidas_costos';
      public $timestamps = false;
}
