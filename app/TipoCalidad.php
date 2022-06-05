<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCalidad extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_calidad';
    public $timestamps = false;
}
