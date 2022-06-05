<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViaticosGastosMenores extends Model
{
    //
    protected $fillable = ['fecha', 'descripcion',
                           'cargo', 'proveedor_acredor', 'empleado_id','proyecto','proyecto_id','tipo','moneda','banco','mes','centrocostos_id'];
    protected $table = 'viaticos_gastos_menores';
    public $timestamps = false;
}
