<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudVehiculo extends Model
{
    protected $table = "vehiculos_solicitud";
    public $timestamps = false;
    protected $fillable = [
        "proyecto_id",
        "fecha_solicitud",
        "folio",
        "unidad_id",
        "tipo_unidad_id",
        "tiempo",
        "fecha_inicio",
        "fecha_fin",
        "ciudad",
        "estado",
        "poblacion",
        "necesidad",
        "solicita_id",
        "empresa",
        "autoriza_id"
    ];
}
