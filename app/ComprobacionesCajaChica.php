<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobacionesCajaChica extends Model
{
    protected $fillable = ['cajachica_id','comprobante','xml','centro_costos_id','empresa','factura_id','uuid','departamento','estado'];
    protected $table = 'comprobaciones_caja_chica';
    public $timestamps = true;
}
