<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaNomina extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'id',
  'UUID',
  'FechaTimbrado',
  'Moneda',
  'TipoDeComprobante',
  'MetodoPago',
  'Serie',
  'Folio',
  'LugarExpedicion',
  'SubTotal',
  'Descuento',
  'Total'
    ];
    protected $table = 'factura_nomina';
    public $timestamps = false;

}
