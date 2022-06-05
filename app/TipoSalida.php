<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoSalida extends Model
{
    protected $fillable = ['nombre','condicion'];
    protected $table = 'tipo_salidas';
    public $timestamps = false;

}
