<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SalidasResguardoDBP extends Model
{
    //
    protected $connection = 'csct';
    protected $fillable = ['fecha','folio','format_salida','descripcion','ubicacion','proyecto_id','tiposalida_id','empleado_entrega_id ','empleado_supervisor_id','empleado_solicita_id','condicion','supervisores_proyectos_id'];
    protected $table = 'salidasresguardo';
    public $timestamps = false;
}
