<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DirectorioTelefonico extends Model
{
    protected $fillable = ['numero_telefonico','proveedor_telefonia','ubicacion', 'comentario', 'empleado_encargado_id'];
    protected $table = 'directorio';
    public $timestamps = false;
}
