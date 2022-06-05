<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Sistema extends Model
{
    protected $fillable = ["nombre","proyecto_id","espeificaciones_material","tag"];
    protected $table = "calidad_sistemas";
    public $timestamps = false;
}
