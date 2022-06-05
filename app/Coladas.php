<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Coladas extends Model
{
    protected $table = 'coladas';
    protected $fillable = ['articulo_id','numero_colada','certificado'];
    public $timestamps = false;
}
