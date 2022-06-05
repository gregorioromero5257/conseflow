<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RimeCalidad extends Model
{

    protected $fillable = [
        "folio",
        "proyecto_id",
        "oc_id",
        'fecha',
        'no_proyecto',
        'factura',
        'empleado_aprobo',
        'empleado_enterado',
        'empleado_reviso',
    ];
    protected $table = 'rime_calidad';
    public $timestamps = false;
}
