<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class MaquinaSoldar extends Model
{
    protected $fillable = [
        "no_control",
        "no_serie",
        "marca",
        "modelo",
        "estado"
    ];
    protected $table = "calidad_maquinas_soldar";
    public $timestamps = false;
}
