<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Controltiempo extends Model

{
    protected $fillable = ['fecha','proyecto_id','empleado_asignado_id','supervisor_id','horas_extras','nave'];
    protected $table = 'control_tiempo';
    public $timestamps = false;
}
