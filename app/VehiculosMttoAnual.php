<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehiculosMttoAnual extends Model
{
    protected $fillable = ["unidad_id","servicio","mes","anio","observaciones","empresa"];
    protected $table = 'vehiculos_mtto_anual';
    public $timestamps = false;
}
