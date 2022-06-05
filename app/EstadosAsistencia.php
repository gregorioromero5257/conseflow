<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadosAsistencia extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'estado_asistencia_registros';
    public $timestamps = false;
}
