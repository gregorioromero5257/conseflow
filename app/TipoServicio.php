<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoServicio extends Model
{
    protected $fillable = ['id', 'nombre'];
    protected $table = 'tipo_servicio';
    public $timestamps = false;
}
