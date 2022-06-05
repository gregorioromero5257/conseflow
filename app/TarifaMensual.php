<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TarifaMensual extends Model
{
    //
      protected $fillable = ['limite_inferior','limite_superior','cuota_fija','porcentaje'];
      protected $table = 'tarifa_mensual';
      public $timestamps = false;
}
