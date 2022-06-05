<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUbicacion extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_ubicacion';
    public $timestamps = false;

}
