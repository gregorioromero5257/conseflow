<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RimeCalidadCliente extends Model
{

    protected $fillable = [
        "proyecto_id",
        "no_proyecto",
        "folio",
        "fecha",
        'reviso_id',
        'enterado_id',
        'aprobo_id',
    ];
    protected $table = 'rime_calidad_cliente';
    public $timestamps = false;
}
