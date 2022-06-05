<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RHControlTiempoEmp extends Model
{
    protected $table = 'rhcontroltiempo_emp';
    protected $fillable = [
        "empleado_id",
        "fecha",
        "horas",
        "proyecto_id",
    ];
}
