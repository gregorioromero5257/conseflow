<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inspeccion extends Model
{
    //
    protected $fillable = ['proyecto', 'lugar_fabricacion','fecha','num_reporte','procedimento_wps','procedimento_pqr','numero_plano','elemento_servicios_id','especimen_id','datos_examinacion_id','supervisor','inspecciono','supervision'];
    protected $table = 'inspeccion';
    public $timestamps = false;
}
