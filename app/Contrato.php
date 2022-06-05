<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contrato extends Model
{
    protected $fillable = ['tipo_ubicacion_id','fecha_ingreso','fecha_fin',
    'motivo_fecha_fin','tipo_nomina_id','empleado_id','horario_id','tipo_contrato_id',
    'proyecto_id','antiguedad_id','condicion','created_at','updated_at','deleted_at'];
    protected $table = 'contratos';
    public $timestamps = false;
}
