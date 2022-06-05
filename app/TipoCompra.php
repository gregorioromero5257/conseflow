<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoCompra extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_compra';
    public $timestamps = false;

}
