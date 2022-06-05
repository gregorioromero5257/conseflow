<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PermisosCrud extends Model
{
    protected $fillable = ['usuario_id','control_id','ruta'];
    protected $table = 'permisos_controles';
    public $timestamps = false;
}
