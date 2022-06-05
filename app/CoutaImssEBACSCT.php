<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CoutaImssEBACSCT extends Model
{
  protected $connection = 'csct';

  protected $fillable = ['nombre','dias','subtotal','meses','anio'];
  protected $table = 'coutas_imss_eba';
  public $timestamps = false;

}
