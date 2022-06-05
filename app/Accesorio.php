<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Accesorio extends Model
{
    protected $fillable = ['id', 'tipo','etiqueta','ns'];
    protected $table = 'accesorio';
    public $timestamps = false;
}
