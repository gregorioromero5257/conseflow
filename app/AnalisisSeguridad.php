<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AnalisisSeguridad extends Model
{
    //
    protected $table = 'analisis_seguridad';
    protected $fillable =
    [
        "ubicacion",
        "numero_permiso",
        "descripcion",
        "empleado_operaciones",
        "empleado_shso",
        "fecha"
    ];
}
