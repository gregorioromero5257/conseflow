<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MovimientosN extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'nombre','movimiento','fecha','salario','nss'

    ];
    protected $table = 'movimientos';
    public $timestamps = false;

}
