<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Receptor extends Model
{
  protected $connection = 'nomina';

  protected $fillable = [
    'factura_nomina_id',
'Rfc',
'Nombre',
'UsoCFDI',
'Curp',
'NumSeguridadSocial',
'FechaInicioRelLaboral',
'Antiguedad',
'TipoContrato',
'Sindicalizado',
'TipoJornada',
'TipoRegimen',
'NumEmpleado',
'Departamento',
'Puesto',
'RiesgoPuesto',
'PeriodicidadPago',
'SalarioBaseCotApor',
'SalarioDiarioIntegrado',
'ClaveEntFed'
  ];
  protected $table = 'receptor';
  public $timestamps = false;
}
