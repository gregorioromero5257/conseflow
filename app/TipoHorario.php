<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoHorario extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_horario';
    public $timestamps = false;
}
