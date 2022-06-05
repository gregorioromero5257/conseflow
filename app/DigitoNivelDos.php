<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DigitoNivelDos extends Model
{
    //
    protected $fillable = ['nombre','nivel','codigo','nivel_uno_digito_id'];
    protected $table = 'nivel_dos_digito';
    public $timestamps = false;
}
