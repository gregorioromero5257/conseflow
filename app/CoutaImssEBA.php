<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoutaImssEBA extends Model
{
  protected $fillable = ['nombre','dias','subtotal','meses','anio'];
  protected $table = 'coutas_imss_eba';
  public $timestamps = false;

}
