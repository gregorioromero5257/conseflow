<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Descuento extends Model
{
    //
      protected $fillable = ['fecha','unidad','total','numero_pago','observaciones','tipo_descuento_id','empleado_id'];
      protected $table = 'descuentos';
      public $timestamps = false;
}
