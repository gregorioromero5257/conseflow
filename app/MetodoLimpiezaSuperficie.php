<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MetodoLimpiezaSuperficie extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'metodo_limpieza_superficie';
    public $timestamps = false;
}
