<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RFCSatProveedor extends Model
{
    protected $table = 'validacion_rfc_proveedores';
    protected $fillable = [
        "proveedor_id",
        "documento",
        "fecha_carga",
        "estado",
        "aviso",
    ];
    public $timestamps = false;
}
