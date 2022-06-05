<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatCatUnidades extends Model
{
    //
    protected $fillable = ['c_unidad','nombre','descripcion'];
    protected $table = 'sat_cat_unidades';
    public $timestamps = false;
}
