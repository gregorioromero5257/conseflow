<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empresas extends Model
{
    protected $fillable = ['id', 'nombre', 'razon','rfc','direccion','representante'];
    protected $table = 'empresas';
    public $timestamps = false;
}
