<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoContrato extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_contratos';
    public $timestamps = false;

}
