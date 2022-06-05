<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoNomina extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_nomina';
    public $timestamps = false;

}
