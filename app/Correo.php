<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Correo extends Model
{
    protected $fillable = ['id', 'nombre', 'empleado_id','condicion'];
    protected $table = 'correos_corporativos';
    public $timestamps = false;
}
