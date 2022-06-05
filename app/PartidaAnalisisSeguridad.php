<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidaAnalisisSeguridad extends Model
{
    protected $table = 'analisis_seguridad_partida';
    protected $fillable =
    [
        "an_seg_id",
        "analisis_id",
        "riesgo_id",
        "recomendacion_id",
        "supervisor_id",
        "estado"
    ];
}
