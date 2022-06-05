<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Proyecto extends Model
{
    protected $fillable = ["proyecto_id","logo_cliente","nombre","estado"];
    protected $table = "calidad_proyectos";
    public $timestamps = false;
}
