<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoUbicacionDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['nombre'];
    protected $table = 'tipo_ubicacion';
    public $timestamps = false;

}
