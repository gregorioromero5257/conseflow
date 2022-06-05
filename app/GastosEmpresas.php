<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GastosEmpresas extends Model
{
    //
    protected $fillable = ['fecha','proveedor_acreedor','proyecto','tipo','tipo_gasto','centro_costos_id','orden_compra','cargo','moneda','mes','orden_compra_id','empresa','folio'];
    protected $table = 'ordenes_compras_gastos';
    public $timestamps = false;
}
