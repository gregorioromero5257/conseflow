<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class OpinionSatProveedor extends Model
{
    protected $table = 'validacion_opinion_proveedores';
    protected $fillable = [
        "proveedor_id",
        "documento",
        "fecha_carga",
        "estado",
        "aviso",
    ];
    public $timestamps = false;
}
