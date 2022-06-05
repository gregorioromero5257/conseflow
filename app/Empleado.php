<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    //
    protected $fillable = ['nombre','ap_paterno','ap_materno', 'sexo', 'lug_nac', 'fech_nac', 'tipo_sangre', 'talla_overol', 'talla_botas','amortizacion','numero_credito','edo_civil_id', 'tipo_ubicacion_id', 'puesto_id','curp', 'rfc', 'nss_imss','fech_alta_imss',
    'fech_ing','id_checador','clave_ine','numero_credencial'];
    protected $table = 'empleados';
}
