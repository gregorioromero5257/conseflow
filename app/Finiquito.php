<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Finiquito extends Model
{
    protected $fillable = ['total', 'aguinaldo_proporcional', 'prima_vacacional', 'vacaciones_proporcional','formato','antiguedad_id'];
    protected $table = 'finiquito';
    public $timestamps = false;
}
