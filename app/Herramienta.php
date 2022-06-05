<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Herramienta extends Model
{
    protected $fillable = ['id', 'descripcion', 'cantidad', 'marca', 'unidad'];
    protected $table = 'herramienta';
    public $timestamps = false;
}
