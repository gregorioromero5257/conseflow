<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    //
      protected $fillable = ['hora_entrada','hora_salida_comida','hora_entrada_comida','hora_salida','estado','pendiente','observaciones','estado_asistencia_id'];
      protected $table = 'registros';
      public $timestamps = false;
}
