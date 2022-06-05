<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadRenta extends Model
{
    //
    protected $fillable = ['unidad_id','proveedor','tiempo','costo_renta'];
    protected $table = 'unidad_renta';
    public $timestamps = false;
}
