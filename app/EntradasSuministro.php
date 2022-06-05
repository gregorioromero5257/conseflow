<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EntradasSuministro extends Model
{
    //
    protected $fillable = ['folio','fecha','cliente_id','proyecto_id'];
    protected $table = 'retardos';
    public $timestamps = false;
}
