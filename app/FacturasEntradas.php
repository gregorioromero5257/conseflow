<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturasEntradas extends Model
{
    //
    protected $fillable = [
      'entrada_id',
    'proveedore_id',
    'total',
    'impuesto_factura_id',
    'uso_factura',
    'comprobante',
    'uuid',
    'uuid_relacionado',
    'descuento',
    'partidas_costos_id','catalogo_centro_costos_id','total_factura','xml','tipo','uuid_xml',
    'poliza','num_poliza','pago_adj'
    ];
    protected $table = 'facturas_entradas';
    public $timestamps = false;
}
