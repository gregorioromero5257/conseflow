<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Escolaridade extends Model
{
    protected $fillable =['grado_id', 'fecha_inicio', 'fecha_termino', 'titulo', 'cedula_prof', 'empleado_id'];
}
