<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class TrazabilidadJuntas extends Model
{
    protected $fillable = ["servicios_sistema_id", "folio", "identificacion"];
    protected $table = "calidad_trazabilidad_juntas";
    public $timestamps = false;
}
