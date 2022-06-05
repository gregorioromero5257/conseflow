<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasFacturasPagos extends Model
{
    //
    protected $fillable =['factura_id','num_parcialidad','saldo_anterior','importe_pagado','saldo_insoluto','partidas_facturas_id','uuid','serie','folio'];
    protected $table = 'partidas_facturas_pagos';
    public $timestamps = false;
}
