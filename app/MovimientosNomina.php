<?php
namespace App;
use Illuminate\Database\Eloquent\Model;



class MovimientosNomina extends Model
{

    protected $fillable = [
    'dias_trabajados',
    'transferencias',
    'efectivos',
    'otros',
    'descuento',
    'infonavit',
    'viticos_alimentos',
    'totales',
    'empleado_id',
    'contrato_id',
    'nomina_id',
    'banco_id',
    'proyecto_id',
    'sueldo_diario'
  ];
    protected $table = 'nominas_movimientos';
    public $timestamps = false;
}
