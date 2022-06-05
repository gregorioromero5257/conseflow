<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturasViaticosPagos extends Model
{
    //
    protected $fillable = ['solicitud_viaticos_id','pda','adjunto'];
    protected $table = 'facturas_viaticos_pagos';
    public $timestamps = false;
}
