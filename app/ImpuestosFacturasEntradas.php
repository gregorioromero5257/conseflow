<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImpuestosFacturasEntradas extends Model
{
    //
      protected $fillable = ['impuesto_id','monto','factura_id'];
      protected $table = 'impuestos_facturas_entradas';
      public $timestamps = false;
}
