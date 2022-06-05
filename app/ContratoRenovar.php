<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContratoRenovar extends Model
{
    protected $fillable = ['contrato_id','condicion'];
    protected $table = 'contratos_renovar';
    public $timestamps = false;
}
