<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiAccesorio extends Model
{
    protected $fillable = [
        "descripcion",
        "modelo",
        "marca",
        "no_serie",
        "condicion",
        "cantidad","eliminado"
    ];
    protected $table = 'ti_accesorios';
    public $timestamps = false;
}
