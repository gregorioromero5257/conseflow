<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{

    protected $fillable = ['folio','serie','emisor_id','tipo_factura_id','cliente_id','uso_factura','fecha_hora','codigo_postal','moneda_id','formapago','metodopago','tipo_cambio','condicion_pago','timbrado','archivo','taxid','clave_ob','orden_ob','uuid','tipo_relacion','factura_id','total','num_operacion'
  ,'ban_ordenante','cuenta_ordenante','adenda','proveeade','uuid_relacionado','fecha_pago','rfc_cuenta_ordenante','rfc_cuenta_beneficiario'];
    protected $table = 'factura';
    public $timestamps = false;
}
