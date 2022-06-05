<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DatosExaminacion extends Model
{
    //
    protected $fillable = ['distancia_al_objeto', 'angulo_aprox_superficie','condicion_superficial'];
    protected $table = 'datos_examinacion';
    public $timestamps = false;
}
