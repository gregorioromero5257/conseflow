<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidaFacturaProveedor extends Model
{
    protected $fillable = ["facturas_proveedor_id", "articulo_id", "cantidad","requ_id"];
    protected $table = 'partidas_facturas_proveedores';
    public $timestamps = false;
}
