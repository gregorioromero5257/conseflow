<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValeSoliVehiculo extends Model
{
    protected $fillable = ['solicitud_id', 'fecha', 'entega_id', 'poliza_id',"empresa"];
    protected $table = 'vehiculos_vale_resguardo';
    public $timestamps = true;
}