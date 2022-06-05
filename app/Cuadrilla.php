<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Cuadrilla extends Model
{
    protected $fillable = ['proyecto_id','supervisor','empleados','fecha'];
    protected $table = 'cuadrilla_trabajo';
    public $timestamps = false;
}
