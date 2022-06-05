<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoutaImssEMA extends Model
{
  protected $fillable = ['nombre','dias','subtotal','mes','anio'];
  protected $table = 'coutas_imss_ema';
  public $timestamps = false;

}
