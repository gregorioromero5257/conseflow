<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdenRedMovimiento extends Model
{
    //
    protected $fillable = ['fecha_movimiento',
  'numero_nomina','concepto','descripcion_comercio','tipo_comercio',
'rfc_comercio','id_movimiento','numero_autorizacion','numero_control_edo_cuenta','abono','valor_pesos','comision_iva','valor_moneda_extranjera',
'descripcion_moneda_extranjera','tipo_cambio_pesos','observaciones','estado','encabezado_movimiento_id'];
    protected $table = 'movimientos_edenred';
    public $timestamps = false;
}
