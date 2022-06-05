<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatVehiculos extends Model
{
    protected $fillable = ['descripcion','centro_costo_id'];
    protected $table = 'vehiculos';
    public $timestamps = false;
}
