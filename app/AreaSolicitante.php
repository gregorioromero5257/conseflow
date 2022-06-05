<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AreaSolicitante extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'area_solicitante';
    public $timestamps = false;

}
