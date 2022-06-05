<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturaProveedor extends Model
{
    protected $fillable = [
        "proveedor_id",
        "fecha_carga",
        "doc_calidad",
        "doc_almacen",
        "oc_id",
        "estado",
        "fecha_programada",
        "mensaje",
        "no_factura",
        "total",
        "moneda",
        "xml_factura",
        "pdf_factura"
    ];
    protected $table = 'facturas_proveedores';
    public $timestamps = false;
}
