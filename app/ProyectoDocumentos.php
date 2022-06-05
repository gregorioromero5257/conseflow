<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ProyectoDocumentos extends Model
{
    //
    protected $fillable = ['proyecto_id','documento'];
    protected $table = 'proyectos_documentos';
    public $timestamps = false;
}
