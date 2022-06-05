<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class NuevoAnalisisSeguridad extends Model
{
    protected $fillable = [
        "ubicacion",
        "no_permiso",
        "descripcion",
        "id_empleado_operaciones",
        "id_empleado_sho",
        "fecha",
        "condicion"
    ];
    protected $table = "analisis_seguridad_nuevo";
}
