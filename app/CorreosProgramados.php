<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CorreosProgramados extends Model
{
    //
    protected $fillable = ['user_id', 'empleado_id','contacto_id','correo','frecuencia','tiempo_rango','tipo_correo','estado'];
    protected $table = 'correos_programados';
    public $timestamps = false;
}
