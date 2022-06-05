<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class HorasExtra extends Model
{
    protected $fillable = ['total_horas_extra', 'asistencia_id','proyecto_id','control_tiempo_id'];
    protected $table = 'horas_extras';
    public $timestamps = false;
}
