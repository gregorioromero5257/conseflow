<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Unidades extends Model
{
    protected $fillable = ['unidad', 'modelo', 'placas', 'ultimo_servicio', 'poliza', 'tarjeta_circulacion', 'verificacion', 'historial_servicio'];
    protected $table = 'unidades';
    public $timestamps = false;
}
