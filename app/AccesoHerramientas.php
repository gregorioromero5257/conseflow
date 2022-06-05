<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AccesoHerramientas extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'accesos_herramientas';
    public $timestamps = false;
}
