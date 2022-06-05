<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    protected $fillable = ['hora_entrada', 'hora_salida_comida', 'hora_entrada_comida', 'hora_salida', 'tipo_horario_id', 'dia_id'];
    public $timestamps = false;
}
