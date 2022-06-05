<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Soldador extends Model
{
    protected $fillable = ["empleado_id","clave","estado"];
    protected $table = "calidad_soldadores";
    public $timestamps = false;
}
