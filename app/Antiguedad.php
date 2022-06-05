<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Antiguedad extends Model
{
    protected $fillable = ['fecha_inicial', 'fecha_final', 'empleado_id', 'empresa_id', 'condicion'];
    protected $table = 'antiguedad';
    public $timestamps = false;
}
