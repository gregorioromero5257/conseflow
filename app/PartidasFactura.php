<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasFactura extends Model
{
  protected $fillable =['productos_servicios_id','unidad_id','cantidad','unidad','numero_identificacion','descripcion','valor_unitario','importe','descuento','impuesto_iva','factura_id','retencion','valor_impuesto','comentario'];
  protected $table = 'partidas_factura';
  public $timestamps = false;
}
