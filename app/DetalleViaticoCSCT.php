<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DetalleViaticoCSCT extends Model
{
    // protected $connection = 'csct';
    protected $connection = 'csct';
    protected $fillable = ['solicitud_viaticos_id','catalogo_conceptos_viaticos_id','transferencia_electronica','efectivo','total','catalogo_concepto_viaticos'];
    protected $table = 'detalle_viaticos';
    public $timestamps = false;
}
