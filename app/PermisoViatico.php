<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PermisoViatico extends Model
{
    protected $fillable = ['propietario_id','empleado_permitido_id','empresa'];
    protected $table = 'permisos_viaticos';
    public $timestamps = false;
}