<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasDBP extends Model
{
    //
      protected $connection = 'csct';
      protected $fillable = ['salida_id','tiposalida_id','lote_id','cantidad','cantidad_retorno'];
      protected $table = 'partidas';
      public $timestamps = false;
}
