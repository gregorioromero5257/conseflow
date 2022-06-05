<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenSolicitud extends Model
{

    protected $fillable = [
        "ruta",
        "solicitud_id",
    ];
    protected $table = "ti_solicitudes_adjunto";
    public $timestamps = false;
}