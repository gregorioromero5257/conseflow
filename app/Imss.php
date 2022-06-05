<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Imss extends Model
{
    //
      protected $fillable = ['ramo','prestacion','base_calculo','porcentaje_trabajador','porcentaje_patron'];
      protected $table = 'imss';
      public $timestamps = false;
}
