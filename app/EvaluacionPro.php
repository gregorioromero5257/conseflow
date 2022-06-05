<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EvaluacionPro extends Model
{
    protected $fillable = [
      'proveedor_id',
      'uno',
      'dos',
      'tres',
      'cuatro',
      'cinco',
      'seis',
      'siete',
      'ocho',
      'nueve','diez',
      'once','doce','trece',
      'catorce','quince','diesiseis',
      'diesisiete','diesiocho','evaluador','fecha'
    ];
    protected $table = 'evaluacion_provee';
    public $timestamps = false;
}
