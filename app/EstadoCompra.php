<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class EstadoCompra extends Model

{

    protected $fillable = ['nombre'];
    protected $table = 'estado_compras';
    public $timestamps = false;

}
