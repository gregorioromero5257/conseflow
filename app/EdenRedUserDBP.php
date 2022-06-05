<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdenRedUserDBP extends Model
{
    protected $conecction = 'csct';
    protected $fillable = ['numero_nomina','nombre_empleado','empleado_id','numero_cuenta','numero_tarjeta','status','correo','puesto','telefono','direccion_entrega','buzon_usuario','asignado'];
    protected $table = 'tarjetas_edenred';
    public $timestamps = false;
}
