<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FacturasViaticos extends Model
{
    //
    protected $fillable = ['solicitud_viaticos_id','pda','adjunto'];
    protected $table = 'facturas_viaticos';
    public $timestamps = false;
}
