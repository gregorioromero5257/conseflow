<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoResguardo extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_resguardo'; 
    public $timestamps = false;
}
