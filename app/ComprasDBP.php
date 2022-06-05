<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ComprasDBP extends Model

{
    protected $connection = 'csct';
    protected $fillable = [
    'folio',
    'ordenes_formato',
    'condicion_pago',
    'periodo_entrega',
    'fecha_orden',
    'lugar_entrega',
    'observaciones',
    'descuento',
    'total',
    'moneda',
    'tipo_cambio',
    'referencia',
    'proveedore_id',
    'estado_id',
    'elabora_empleado_id',
    'autoriza_empleado_id',
    'condicion',
    'cie',
    'sucursal',
    'comentario_condicion_pago',
    'conrequisicion',
    'fecha_probable_pago',
    'prioridad','oc_conserflow'
    ];
    protected $table = 'ordenes_compras';
    public $timestamps = false;

}
