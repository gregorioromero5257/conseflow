<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Torque extends Model
{
    protected $fillable = [
        "proyecto_id",
        "ubicacion",
        "fecha",
        "folio",
        "sistema_id",
        "referencia",
        "diametro",
        "material",
        "procedimiento",
        "equipo1_id",
        "secuencia_1",
        "equipo2_id",
        "secuencia_2",
        "inspecciona1_id",
        "inspecciona2",
        "aprueba"
    ];
    protected $table = "calidad_torque";
    public $timestamps = true;
}
