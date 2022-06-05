<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class InspeccionCalidad extends Model
{

    protected $fillable = [
        "rime_calidad_id",
        "cantidad",
        "articulo_id",
        "marca",
        "modelo",
        "no_serie",
        "no_certificado",
        "tipo_mecanico",
        "tipo_estructura",
        "tipo_anticorrosivo",
        "tipo_electrico",
        "tipo_instrumentacion",
        "ins_mat_factura",
        "ins_mat_edo_fisico",
        "ins_mat_dimesiones",
        "ins_mat_normativa",
        "ins_mat_nacionalidad",
        "ins_mat_cetificado",
        "ins_mat_cet_cumpl",
        "ins_mat_coladas",
        "ins_mat_documentos",
        "ins_equi_recibe_cert",
        "ins_equi_recibe_manual",
        "ins_equi_recibe_repuesto",
        "ins_equi_recibe_kit",
        "ins_equi_rango",
        "observaciones",
        "certificado_pdf",
        "tiene_certificado", // El articulo tiene certificado
        "cantidad_equipos_cert" // Cantidad de articulos que avala el certificado
    ];
    protected $table = 'inspecciones_calidad';
    public $timestamps = false;
}
