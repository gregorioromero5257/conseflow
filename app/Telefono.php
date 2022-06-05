<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    protected $fillable = ['id', 'nombre', 'empleado_id', 'condicion'];
    protected $table = 'telefonos_corporativos';
    public $timestamps = false;
}
