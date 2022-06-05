<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoSW extends Model
{
    protected $fillable = ['id', 'nombre'];
    protected $table = 'tipo_sw';
    public $timestamps = false;
}
