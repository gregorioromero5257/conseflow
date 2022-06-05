<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CajaChicaSolicitudDBP extends Model
{
    //
    protected $connection = 'csct';
    protected $table = 'solicitud_caja_chica';
}
