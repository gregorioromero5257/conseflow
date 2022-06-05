<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DigitoNivelUno extends Model
{
    //
    protected $fillable = ['nombre','codigo','nivel'];
    protected $table = 'nivel_uno_digito';
    public $timestamps = false;
}
