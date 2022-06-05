<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OtroPago extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'factura_nomina_id',
  'TipoOtroPago',
  'Clave',
  'Concepto',
  'Importe'
    ];
    protected $table = 'otro_pago';
    public $timestamps = false;
}
