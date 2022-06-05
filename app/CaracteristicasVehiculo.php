<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CaracteristicasVehiculo extends Model
{

    protected $table = 'caracteristicas_vehiculo';
    protected $fillable = ['puerta','clave','capacidad','motor','colores','cilindros','transporte','unidad'];
    public $timestamps = false;
}
