<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class InspeccionVisual  extends Model
{
    protected $fillable = [
        "tabla_aceptacion",
        "criterio_aceptacion",
        "servicios_sistema_id",
        "examinacion_distancia",
        "examinacion_angulo",
        "lugar",
        "empleado_inspecciona_id",
        "empleado_supervisa_id",
        "aprobado",
        "nombre_proyecto",
        "procedimiento_id",
        "proyecto_id",
        "especimen_presion",
        "especimen_tuberia",
        "especimen_placa",
        "especimen_perfil",
        "acabado_burdo",
        "acabado_maquinado",
        "acabado_esmerilado",
        "examinacion_directa",
        "examinacion_remota",
        "examinacion_translucida",
        "iluminacion_natural",
        "iluminacion_mano",
        "iluminacion_incan",
        "iluminacion_reflector",
        "accesorios_camara",
        "accesorios_bridge",
        "accesorios_seven",
        "accesorios_hilo",
        "limpieza_meca",
        "limpieza_solvente",
        "limpieza_maquina",
        "folio",
        "fecha",
        "clave_inspector"
    ];
    protected $table = "calidad_inspecciones_visual";
    public $timestamps = false;
}
