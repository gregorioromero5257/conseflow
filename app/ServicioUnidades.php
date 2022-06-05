<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioUnidades extends Model
{
    //
    protected $fillable = ['fecha_servicio','fecha_entrega','kilometraje', 'proveedor',
    'responsable','total','comprobante','unidad_id'];
    protected $table = 'servicio_unidades';
    public $timestamps = false;
}
