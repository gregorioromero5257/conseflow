<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ResguardoUnidad extends Model
{
    protected $fillable = ['empleado_id','poliza_id','fecha'];
    protected $table = 'resguardo_unidad';
    public $timestamps = false;
}
