<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Existencia extends Model
{
    //
      protected $fillable = ['articulo_id','almacene_id','nivel_id','stand_id','cantidad','id_lote'];
      protected $table = 'existencias';
      public $timestamps = false;
}
