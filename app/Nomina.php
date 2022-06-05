<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nomina extends Model
{
  protected $fillable = ['fecha_inicial','fecha_final','periodo','tipo_nomina','empresa'];
	protected $table = 'nomina';
	public $timestamps = false;
}
