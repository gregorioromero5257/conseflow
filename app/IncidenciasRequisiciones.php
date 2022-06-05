<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class IncidenciasRequisiciones extends Model
{
    //
    protected $fillable = ['requisicion_id', 'pda', 'articulo_servicio','articulo_servicio_id','comentario'];
    protected $table = 'incidencias_requisiciones';
    public $timestamps = false;
}
