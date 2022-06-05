<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrecioAlmacen extends Model
{
    protected $fillable = ['articulo_id','req_id','precio','moneda',"partida_id"];
    protected $table = 'precios_almacen';
    public $timestamps = false;
}
