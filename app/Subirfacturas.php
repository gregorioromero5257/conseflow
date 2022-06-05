<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subirfacturas extends Model
{
    //
    protected $fillable = ['uuid', 'nombre_archivo', 'ordenes_compras_id'];
    protected $table = 'subir_factura';
    public $timestamps = false;
}
