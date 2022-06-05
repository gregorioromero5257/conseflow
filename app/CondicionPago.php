<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CondicionPago extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'condicion_pago';
    public $timestamps = false;
}
