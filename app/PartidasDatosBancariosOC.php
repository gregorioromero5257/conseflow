<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasDatosBancariosOC extends Model
{
    //
    protected $fillable = ['banco','clabe','cuenta','orden_compra_id','titular'];
    protected $table = 'partidas_datos_bancarios_oc';
    public $timestamps = false;
}
