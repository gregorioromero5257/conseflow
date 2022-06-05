<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RequisicionhasordencomprasDBP extends Model
{
  protected $connection = 'csct';
     protected $fillable = ['requisicione_id', 'orden_compra_id','articulo_id','servicio_id','cantidad','precio_unitario','condicion','comentario','antigua','vehiculo_id'];
    protected $table = 'requisicion_has_ordencompras';
    public $timestamps = false;
}
