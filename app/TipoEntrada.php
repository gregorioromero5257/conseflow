<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoEntrada extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_entrada';
    public $timestamps = false;

}
