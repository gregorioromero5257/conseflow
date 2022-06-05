<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiVideo extends Model
{
    protected $fillable = [
        "descripcion",
        "no_serie",
        "condicion",
        "cantidad","eliminado"
    ];
    protected $table = 'ti_video';
    public $timestamps = false;
}
