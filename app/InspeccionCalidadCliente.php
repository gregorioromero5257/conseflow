<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspeccionCalidadCliente extends Model
{

    protected $fillable = [
        "partida",
        "rime_id",
        "no_certificado",
        "articulo",
        "cantidad",
        "marca",
        "modelo",
        "tag",
        "certificado",
        "no_serie",
        "colada",
        "tipo_equipo",
        "tipo_inssto",
        "inspeccion_certificado",
        "inspeccion_manuales",
        "inspeccion_respuesto",
        "inspeccion_kids",
        "inspeccion_dagnos",
        "inspeccion_embalaje",
        "inspeccion_almacenamiento",
        "inspeccion_conservacion",
        "observaciones"
    ];
    protected $table = 'inspecciones_calidad_cliente';
    public $timestamps = false;
}
