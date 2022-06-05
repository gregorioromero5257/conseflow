<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Referencia extends Model
{
    protected $fillable = ['id', 'nombre', 'ocupacion', 'direccion', 'telefono', 'empleado_id', 'condicion'];
    protected $table = 'referencias_conocidos';
    public $timestamps = false;

}
