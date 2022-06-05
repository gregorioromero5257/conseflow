<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Recibos extends Model
{
     protected $fillable = ['periodo', 'periodo_inicial', 'periodo_final', 'observaciones', 'total_a_pagar'];
    protected $table = 'recibos';
    public $timestamps = false;
}
