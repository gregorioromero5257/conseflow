<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodigoAgrupador extends Model
{
    protected $fillable = ['pertenece_id', 'nivel','nuvel_estado','codigo_agrupador','nombre_cuenta_sub'];
    protected $table = 'codigo_agrupador';
    public $timestamps = false;
}
