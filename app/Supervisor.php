<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Supervisor extends Model
{
    protected $fillable = ['supervisor_id','empleado_asignado_id'];
    protected $table = 'supervisores_empleado';
    public $timestamps = false;
}
