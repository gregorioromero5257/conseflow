<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadMedida extends Model
{
    //
    protected $fillable = ['clave','nombre'];
    protected $table = 'unidades_medida';
    public $timestamps = false;
}
