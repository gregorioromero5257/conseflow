<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AntiguedadDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['fecha_inicial', 'fecha_final', 'empleado_id', 'empresa_id', 'condicion'];
    protected $table = 'antiguedad';
    public $timestamps = false;
}
