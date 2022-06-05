<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoDescuento extends Model
{
    //
      protected $fillable = ['fecha','cantidad','numero_pago','descuento_id'];
      protected $table = 'pagos_descuentos';
      public $timestamps = false;
}
