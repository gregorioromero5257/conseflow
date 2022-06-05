<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class Recepcion extends Model
{
    protected $fillable =
    [
        "folio",
        "proyecto_id",
        "fecha",
        "hora",
        "oc_id",
        "coincide_solicitado",
        "danios_visivles",
        "condiciones_aceptables",
        "metarial_aceptado",
        "motivo",
        "unidad",
        "placas",
        "no_almacen",
        "observaciones",
        "persona_entrega",
        "empleado_recibe"
    ];
    protected $table = "calidad_recepcion";
}
