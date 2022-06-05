<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ServicioCheck extends Model
{

  protected $fillable = ['fecha','rhoc_id'];
  protected $table = 'servicio_check';
  public $timestamps = false;
}
