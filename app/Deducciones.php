<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Deducciones extends Model
{
    //
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'factura_nomina_id',
  'TipoDeduccion',
  'Clave',
  'Concepto',
  'Importe'
    ];
    protected $table = 'deducciones';
    public $timestamps = false;
}
