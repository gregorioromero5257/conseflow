<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ColadaInspeccion  extends Model
{
    protected $table = 'coladas_inspeccion';
    protected $fillable = ['inspeccion_id','cantidad','colada'];
    public $timestamps = false;
}
