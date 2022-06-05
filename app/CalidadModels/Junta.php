<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

use function PHPSTORM_META\map;

class Junta  extends Model
{
    protected $fillable = [
        "nombre",
        "diametro",
        "spool_id",
        "colada_uno_id",
        "espesor",
        "colada_dos_id",
        "soldador_proyecto_id",
        "procedimiento_id",
        "junta",
        "hoja",
        "fecha_habilitado",
        "fecha_soldado",
        "fecha_inspeccion"
    ];
    protected $table = "calidad_juntas";
    public $timestamps = false;
}
