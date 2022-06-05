<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rces extends Model
{
    //
      protected $fillable = ['requisicion_compra','entradas','salidas','orden_compra_id','folio_csct'];
      protected $table = 'rces';
      public $timestamps = false;
}
