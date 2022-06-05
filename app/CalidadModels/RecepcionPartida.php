<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class RecepcionPartida extends Model
{
    protected $fillable =
    [
        "recepcion_id",
        "cantidad",
        "articulo_id"
    ];
    protected $table = "calidad_partida_recepcion";
}
