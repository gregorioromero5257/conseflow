<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Percepciones extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'factura_nomina_id',
  'TipoPercepcion',
  'Clave',
  'Concepto',
  'ImporteGravado',
  'ImporteExento'
    ];
    protected $table = 'percepciones';
    public $timestamps = false;
}
