<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FactorIntegracion extends Model
{
    //
      protected $fillable = ['anio_servicio','dias_aguinaldo','dias_vacaciones','prima_vacacional','factor_integracion'];
      protected $table = 'catalogo_factor_integracion';
      public $timestamps = false;
}
