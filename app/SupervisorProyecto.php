<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SupervisorProyecto extends Model
{
    protected $fillable = ['proyecto_id','supervisor_id','activo'];
    protected $table = 'supervisores_proyectos';
    public $timestamps = false;
}
