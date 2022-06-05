<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class NomenclaturaTrazabilidad extends Model
{
    protected $fillable =
    [
        "inpec_trazab_id",
        "nomen_id"
    ];
    protected $table = "calidad_nomens_trazab";
    public $timestamps = false;
}
