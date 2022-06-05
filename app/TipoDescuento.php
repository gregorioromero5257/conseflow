<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDescuento extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_descuentos';
    public $timestamps = false;

}
