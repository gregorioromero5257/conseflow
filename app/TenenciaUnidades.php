<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TenenciaUnidades extends Model
{
    //
    protected $fillable = ['fecha','comprobante','unidad_id','anio','folio','costo'];
    protected $table = 'tenencia_unidades';
    public $timestamps = false;
}
