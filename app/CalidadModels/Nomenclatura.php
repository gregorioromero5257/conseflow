<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Nomenclatura  extends Model
{
    protected $fillable = [
        "clave",
        "nombre",
    ];
    protected $table = "calidad_nomenclaturas";
    public $timestamps = false;
}
