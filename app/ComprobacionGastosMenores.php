<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ComprobacionGastosMenores extends Model
{
    //
    protected $fillable = ['viaticos_gastos_menores_id','fecha','foliosat','facturainterna','proveedor_acreedor','concepto','importediecisies','importecero','iva',
    'derechos','otros_impuestos','no_deducible','total','catalogo_conceptos_viaticos_id','folio_peaje','adjunto'];
    protected $table = 'comprobacion_gastos_menores';
    public $timestamps = false;
}
