<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResponsableSoliVehiculos extends Model
{
    protected $table = "vehiculos_solicitud_responsables";
    public $timestamps = false;
    protected $fillable = [
        "solicitud_id",
        "empleado_id"
    ];
}
