<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PartidaRequisicionDoc extends Model
{
    protected $fillable =['documento_id','partidarequisicione_art','partidarequisicione_req','partidarequisicione_serv','empleado_valida'];
    protected $table = 'partidarequisicion_documentos';
    public $timestamps = false;


}
