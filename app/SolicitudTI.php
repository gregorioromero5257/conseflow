<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudTI extends Model
{

    protected $fillable = [
        "asunto",
        "id",
        "asunto",
        "tipo_id",
        "fecha_hora_recibido",
        "fecha_hora_terminado",
        "estado_id",
        "prioridad",
        "descripcion",
        "usuario_id"
    ];
    protected $table = "ti_solicitudes";
    public $timestamps = false;
}
