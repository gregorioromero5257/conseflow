<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosEmpleados extends Model

{

    protected $fillable = ['fecha_solicitud','motivo','formato_permiso','fecha_inicio','fecha_fin','tipo_salida','fech_temp','hora_salida','hora_regreso','goce','condicion','solicita_empleado_id','autoriza_empleado_id'];
    protected $table = 'solicitud_permisos';
    public $timestamps = false;
    
}