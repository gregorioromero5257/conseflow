<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoAgrupador extends Model
{
    //
    protected $fillable = ['nombre','nombre_corto'];
    protected $table = 'proyecto_agrupador';
    public $timestamps = false;
}
