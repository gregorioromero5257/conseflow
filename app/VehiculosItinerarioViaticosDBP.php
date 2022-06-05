<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VehiculosItinerarioViaticosDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['solicitud_viaticos_id','unidad','km_inicial','empleado_operador_id'];
    protected $table = 'vehiculos_itinerario_viaticos';
    public $timestamps = false;
}
