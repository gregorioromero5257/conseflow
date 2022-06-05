<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturasGastosEmpresa extends Model
{
    //
    protected $fillable = ['orden_compra_gasto_id', 'proveedore_id','total','uso_factura_id','comprobante','uuid','uuid_relacionado','descuento','partidas_costos_id','catalogo_centro_costos_id'];
    protected $table = 'facturas_gastos_empresa';
    public $timestamps = false;
}
