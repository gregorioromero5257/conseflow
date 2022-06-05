<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalDetalles extends Model
{
    protected $fillable = ['solicitud_viaticos_id','empleado_id'];
    protected $table = 'personal_detalles';
    public $timestamps = false;
}
