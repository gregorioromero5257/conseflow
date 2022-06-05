<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidaCotizacionCompra extends Model
{
  protected $fillable = ['orden_compra_id','cotizacion_id'];
  protected $table = 'partida_cotizacion_compra';
  public $timestamps = false;
}
