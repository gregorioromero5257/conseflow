<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguridadRecomendacion extends Model
{

  protected $fillable = ["nombre"];
  protected $table = "seguridad_recomendacion";
  public $timestamps = false;
}
