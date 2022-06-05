<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contacto extends Model
{
    protected $fillable = ['correo_electronico','tel_celular','tel_casa','tel_emergencia','contacto_emergencia','empleado_id'];
    public $timestamps = false;
    protected $table = 'contacto_empleados';
}
