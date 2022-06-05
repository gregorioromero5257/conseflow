<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguridadAnalisis extends Model
{

    protected $fillable =
    [
        "secuencia_id",
        "riesgo_id",
        "recomendacion_id"
    ];
    protected $table = "seguridad_analisis";
    public $timestamps = false;
}
