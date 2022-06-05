<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasInspeccion extends Model
{
    //
    protected $fillable = ['diametro', 'espesor','indicacion','evaluacion','id_soldador','observaciones','materiales_servicios_sol_id','material_uno','material_dos','inspeccion_id'];
    protected $table = 'partidas_inspeccion';
    public $timestamps = false;
}
