<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class InspeccionSoldaduraJunta  extends Model
{
    protected $fillable = [
        "junta_id",
        "fecha",
        "aceptado",
        "comentarios"
    ];
    protected $table = "inspecciones_soldaduras_juntas";
    public $timestamps = false;
}
