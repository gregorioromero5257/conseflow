<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiRed extends Model
{
    protected $fillable = [
        "descripcion",
        "marca",
        "no_serie",
        "mac",
        "ip",
        "observaciones",
        "condicion",
        "cantidad",'eliminado'
    ];
    protected $table = 'ti_red';
    public $timestamps = false;
}
