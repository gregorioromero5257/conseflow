<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestoDBP extends Model
{
    //
      protected $connection = 'csct';

      protected $fillable = ['orden_compra_id','tipo','porcentaje','retenido'];
      protected $table = 'impuestos';
      public $timestamps = false;
}
