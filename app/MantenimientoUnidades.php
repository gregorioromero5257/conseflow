<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MantenimientoUnidades extends Model
{
    //
    protected $fillable = ['proveedor','responsable','total', 'fecha_servicio',
    'kilometraje', 'fecha_entrega','fecha_prox_rev','kilometraje_revision','factura','unidad_id'];
    protected $table = 'mantenimiento_unidades';
    public $timestamps = false;
}
