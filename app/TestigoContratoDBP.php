<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TestigoContratoDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['contrato_id', 'empleado_id'];
    protected $table = 'testigos_contratos';
    public $timestamps = false;
}
