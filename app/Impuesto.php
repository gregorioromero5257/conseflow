<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Impuesto extends Model
{
    //
      protected $fillable = ['orden_compra_id','tipo','porcentaje','retenido'];
      protected $table = 'impuestos';
      public $timestamps = false;
}
