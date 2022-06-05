<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoutaImssEMACSCT extends Model
{
  protected $connection = 'csct';

  protected $fillable = ['nombre','dias','subtotal','mes','anio'];
  protected $table = 'coutas_imss_ema';
  public $timestamps = false;

}
