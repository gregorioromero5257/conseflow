<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TotalSolicitudViaticosDBP extends Model
{
    //
    protected $connection = 'csct';
    protected $table = 'totales_solicitud_viaticos';
}
