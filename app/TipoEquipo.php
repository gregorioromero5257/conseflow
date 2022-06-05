<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEquipo extends Model
{
    protected $fillable = ['id', 'nombre'];
    protected $table = 'tipo_equipo';
    public $timestamps = false;
}
