<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Partidas extends Model
{
    //
      protected $fillable = ['salida_id','tiposalida_id','lote_id','cantidad','cantidad_retorno',"precio_asignado"];
      protected $table = 'partidas';
      public $timestamps = false;
}
