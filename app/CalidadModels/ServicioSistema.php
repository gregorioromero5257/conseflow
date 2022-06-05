<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class ServicioSistema extends Model
{
    protected $fillable = ["sistema_id","servicio_id","plano"];
    protected $table = "servicios_sistema";
    public $timestamps = false;
}