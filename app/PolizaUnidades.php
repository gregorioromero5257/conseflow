<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PolizaUnidades extends Model
{
    //
    protected $fillable = ['proveedor','numero_poliza','fecha_inicio','fecha_termino','comprobante','unidad_id','numero_inciso'];
    protected $table = 'poliza_unidades';
    public $timestamps = false;
}
