<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ParticipantesNuevoAnalisis extends Model
{
    protected $table = 'analisis_seguridad_participantes';
    protected $fillable =
    [
        "analisis_id",
        "empleado_id",
        "estado"
    ];
}
