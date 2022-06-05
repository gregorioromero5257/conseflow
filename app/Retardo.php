<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Retardo extends Model
{
    //
      protected $fillable = ['asistencia_id','tiempo_retardo_entrada','tiempo_retardo_comida'];
      protected $table = 'retardos';
      public $timestamps = false;
}
