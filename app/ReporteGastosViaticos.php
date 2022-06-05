<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReporteGastosViaticos extends Model
{
    //
    protected $fillable = ['solicitud_viaticos_id','fecha','foliosat','facturainterna',
                           'proveedor_acreedor','concepto','importediecisies','importecero',
                           'iva','derechos','otros_impuestos','no_deducible','total','adjunto',
                           'pda','num_reporte','condicion','catalogo_conceptos_viaticos_id','beneficiario_id','folio_peaje','xml',
                           'empleado_user_id','hora_dia_subida'];
    protected $table = 'reporte_gastos_viaticos';
    public $timestamps = false;
}
