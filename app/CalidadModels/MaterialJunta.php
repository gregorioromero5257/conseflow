<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class MaterialJunta  extends Model
{
    protected $fillable = [
        "nombre_corto",
        "colada_inspeccion_id",
    ];
    protected $table = "calidad_juntas_materiales";
    public $timestamps = false;
}
