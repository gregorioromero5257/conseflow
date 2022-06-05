<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class CertificacionProcedimiento  extends Model
{
    protected $fillable = [
        "wpq",
        "fecha_certificacion",
        "certificado",
        "folio",
        "procedimiento_id",
        "soldador_id"
    ];
    protected $table = "certificaciones_procedimiento";
    public $timestamps = false;
}
