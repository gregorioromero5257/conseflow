<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class SoldadorProyecto extends Model
{
    protected $fillable = [
        "certificacion_procedimiento_id",
        "proyecto_id",
        "maquina_id"
    ];
    protected $table = "calidad_soldador_proyecto";
    public $timestamps = false;
}
