<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PersonalDetallesDBP extends Model
{
    protected $connection = 'csct';
    protected $fillable = ['solicitud_viaticos_id','empleado_id'];
    protected $table = 'personal_detalles';
    public $timestamps = false;
}
