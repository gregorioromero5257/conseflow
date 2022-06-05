<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguridadRiesgo extends Model
{

  protected $fillable = ["nombre"];
  protected $table = "seguridad_riesgo";
  public $timestamps = false;
}
