<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class PartidaTorque extends Model
{
    protected $fillable = [
        "item",
        "ubicacion",
        "brida_id",
        "torque",
        "torque_id"
    ];
    protected $table = "calidad_torque_partida";
    public $timestamps = false;
}
