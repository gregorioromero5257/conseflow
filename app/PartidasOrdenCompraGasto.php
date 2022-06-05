<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasOrdenCompraGasto extends Model
{
    //
    protected $fillable = [
    'orden_compra_gasto_id',
    'articulo_id',
    'servicio_id',
    'cantidad',
    'precio_unitario',
    'condicion',
    'comentario',
    'antigua',
    'vehiculo_id'
    ];
    protected $table = 'partidas_orden_compra_gasto';
    public $timestamps = false;
}
