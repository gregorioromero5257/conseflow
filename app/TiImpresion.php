<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiImpresion extends Model
{
    protected $fillable = [
        "descripcion",
        "modelo",
        "marca",
        "no_serie",
        "condicion",
        "cantidad",
        "mac","eliminado"
    ];
    protected $table = 'ti_impresoras';
    public $timestamps = false;
}
