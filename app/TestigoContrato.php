<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestigoContrato extends Model
{
    //
    protected $fillable = ['contrato_id', 'empleado_id'];
    protected $table = 'testigos_contratos';
    public $timestamps = false;
}
