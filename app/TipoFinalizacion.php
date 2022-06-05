<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoFinalizacion extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_fin_contrato';
    public $timestamps = false;
}
