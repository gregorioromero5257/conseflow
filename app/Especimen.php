<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Especimen extends Model
{
    //
    protected $fillable = ['nombre', 'especificacion_material'];
    protected $table = 'especimen';
    public $timestamps = false;
}
