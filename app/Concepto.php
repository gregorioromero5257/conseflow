<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Concepto extends Model
{
  protected $connection = 'nomina';

  protected $fillable = [
    'factura_nomina_id',
'ClaveProdServ',
'Cantidad',
'ClaveUnidad',
'Descripcion',
'ValorUnitario',
'Importe',
'Descuento' 
  ];
  protected $table = 'concepto';
  public $timestamps = false;
}
