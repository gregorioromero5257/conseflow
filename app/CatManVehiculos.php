<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CatManVehiculos extends Model
{
    protected $fillable = ['descripcion','codigo','marca','comentario','centro_costo_id'];
    protected $table = 'cat_mantenimiento_vehiculos';
    public $timestamps = false;
}
