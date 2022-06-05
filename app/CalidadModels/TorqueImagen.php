<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class TorqueImagen extends Model
{
    protected $fillable = [
        "torque_id",
        "imagen",
        "tipo",
        "posision"
    ];
    protected $table = "calidad_torque_imagenes";
    public $timestamps = true;
}
