<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Precios extends Model
{
    protected $fillable = ['precio_unitario','iva','descuento','total','moneda','tipo_cambio','fecha_compra','lote_id'];
    protected $table = 'precios';
    public $timestamps = false;
}
