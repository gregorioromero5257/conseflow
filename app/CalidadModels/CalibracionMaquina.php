<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class CalibracionMaquina extends Model
{
    protected $fillable = [
        "fecha",
        "maquina_id",
        "no_certificado",
        "documento"
    ];
    protected $table = "calidad_calibracion_maquinas";
    public $timestamps = false;
}
