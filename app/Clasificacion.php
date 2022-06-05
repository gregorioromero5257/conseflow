<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clasificacion extends Model
{
    protected $fillable = ['id', 'nombre'];
    protected $table = 'clasificacion';
    public $timestamps = false;
}
