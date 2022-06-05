<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestoFactura extends Model
{
    //
      protected $fillable = ['tipo','nombre','porcentaje'];
      protected $table = 'impuestos_facturas';
      public $timestamps = false;
}
