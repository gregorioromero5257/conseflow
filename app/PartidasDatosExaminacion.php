<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasDatosExaminacion extends Model
{
    //
    protected $fillable = ['datos_examinacion', 'tipo','id_tiam'];
    protected $table = 'partidas_datos_examinacion';
    public $timestamps = false;
}
