<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Procedimiento extends Model
{
    protected $fillable = ["clave","descripcion","wps","pqr","tipo_preparacion","material_aporte"];
    protected $table = "calidad_proced_soldadura";
    public $timestamps = false;
}
