<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Spool extends Model
{
    protected $fillable = ["no","servicio_sistema_id"];
    protected $table = "calidad_spools";
    public $timestamps = false;
}
