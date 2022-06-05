<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasViaticosPagos extends Model
{
  protected $fillable = ['solicitud_viaticos_id', 'fecha_pago', 'importe_te', 'importe_efectivo','pda','adjunto','beneficiario_id','banco'];
  protected $table = 'partidas_viaticos_pagos';
  public $timestamps = false;
}
