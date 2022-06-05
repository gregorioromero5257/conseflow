<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NominaC extends Model
{
  protected $connection = 'nomina';

  protected $fillable = [
    'factura_nomina_id',
'Version',
'TipoNomina',
'FechaPago',
'FechaInicialPago',
'FechaFinalPago',
'NumDiasPagados',
'TotalPercepciones',
'TotalDeducciones',
'TotalOtrosPagos'
  ];
  protected $table = 'nomina_c';
  public $timestamps = false;
}
