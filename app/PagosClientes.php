<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagosClientes extends Model
{
    //
    protected $fillable = ['fecha','concepto','pago_usd','tipo_cambio','abonos','proyecto_id','cliente_id'];
    protected $table = 'pagos_cliente';
    public $timestamps = false;
}
