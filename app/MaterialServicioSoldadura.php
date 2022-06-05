<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MaterialServicioSoldadura extends Model
{
    //
    protected $fillable = ['servicio_id','soldadura','material_uno','material_dos','proyecto','diametro','espesor','observaciones'];
    protected $table = 'materiales_servicios_sol';
    public $timestamps = false;
}
