<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteAlmacenDBP extends Model
{
      //
      protected $connection = 'csct';

      protected $fillable = ['lote_id','almacene_id','nivel_id','stand_id','cantidad','stocke_id','articulo_id',
      'condicion','comentario','codigo_barras','lote_almacen_id_cfw'];
      protected $table = 'lote_almacen';
      public $timestamps = false;
}
