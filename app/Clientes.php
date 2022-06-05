<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Clientes extends Model
{
    protected $fillable = ['nombre', 'rfc', 'calle','numero_exterior','numero_interior','domicilio_alterno','codigo_postal','colonia','municipio','entidad_federativa','contacto','telefono','ejecutivo_asignado_id'];
    protected $table = 'clientes';
    public $timestamps = false;
}
