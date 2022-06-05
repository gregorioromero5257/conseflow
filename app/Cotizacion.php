<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Cotizacion extends Model

{

    protected $fillable = ['folio','adjunto','proveedor_id'];
    protected $table = 'cotizaciones';
    public $timestamps = false;

}
