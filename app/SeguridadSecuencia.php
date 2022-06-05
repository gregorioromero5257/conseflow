<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SeguridadSecuencia extends Model
{

  protected $fillable = ['nombre'];
  protected $table = 'seguridad_secuencias';
  public $timestamps = false;
}
