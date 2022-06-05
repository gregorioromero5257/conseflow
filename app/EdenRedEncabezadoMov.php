<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EdenRedEncabezadoMov extends Model
{
    //
    protected $fillable = ['dato_uno','dato_dos'];
    protected $table = 'encabezado_movimiento_edenred';
    public $timestamps = false;
}
