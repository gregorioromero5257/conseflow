<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ValidacionHorario extends Model
{
    protected $fillable = ['h_entrada', 'h_scomida', 'h_ecomida', 'h_salida', 'registro_id'];
    protected $table = 'validacion_horario';
    public $timestamps = false;
}
