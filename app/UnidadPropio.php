<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UnidadPropio extends Model
{
    //
    protected $fillable = ['unidad_id','propietario'];
    protected $table = 'unidad_propio';
    public $timestamps = false;
}
