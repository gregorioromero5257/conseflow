<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class requisicionhasordencompras extends Model
{
     protected $fillable = [
       'requisicione_id',
      'orden_compra_id',
      'articulo_id',
      'servicio_id',
      'cantidad',
      'precio_unitario',
      'condicion',
      'comentario',
      'antigua',
      'vehiculo_id',
      'imagen','asociada',
      'item','especificacion'
    ];
    protected $table = 'requisicion_has_ordencompras';
    public $timestamps = false;
}
