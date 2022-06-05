<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CostosProyectosAdmin extends Model
{
    protected $fillable = ['proyecto_id','porcentaje','catalogo_centro_costos_id','compra_id'];
    protected $table = 'costos_proyectos_administrativos';
    public $timestamps = false;
}
