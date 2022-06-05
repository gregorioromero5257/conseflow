<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagoPrestamo extends Model
{
    protected $fillable = ['fecha_pago','cantidad_a_pagar','numero_pago','prestamo_id'];
      protected $table = 'pagos_prestamos';
      public $timestamps = false;
}
