<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServiciosInspeccion extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'servicios_inspeccion';
    public $timestamps = false;
}
