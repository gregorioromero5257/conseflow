<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ViaticoReporte extends Model
{
    protected $fillable = [
        "num_empleado",
        "fecha_alta",
        "fecha_baja",
        "fecha_reingreso",
        "tipo_contrato",
        "ap_paterno",
        "ap_materno",
        "nombre",
        "riesgo_trabajo",
        "sdi",
        "sbc",
        "nss",
        "rfc",
        "curp",
        "empresa"
    ];
    protected $table = 'empleados_viaticos';
    public $timestamps = false;
}
