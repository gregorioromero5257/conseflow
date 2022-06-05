<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Servicio extends Model
{
    protected $fillable = ["nombre"];
    protected $table = "calidad_servicios";
    public $timestamps = false;
}