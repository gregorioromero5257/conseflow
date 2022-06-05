<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Brida extends Model
{
    protected $fillable = [
        "id",
        "clase",
        "diametro_brida",
        "diametro_tornillo",
        "longitud_esparrago",
        "medida_tuerca",
        "imagen"
    ];
    protected $table = "calidad_bridas";
    public $timestamps = true;
}
