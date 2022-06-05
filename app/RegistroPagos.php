<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroPagos extends Model
{
    protected $fillable = ['cliente_id','banco_contabilidad_id','fecha_entrega','fecha_emision','dias_credito','tipo_dias','fecha_corte','total','fecha_pago','condicion'];
    protected $table = 'registro_pagos';
    public $timestamps = false;
}
