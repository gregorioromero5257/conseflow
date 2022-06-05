<?php

namespace App\CalidadModels;

use Illuminate\Database\Eloquent\Model;

class ProeveedorUnidad extends Model
{
    protected $fillable = ["proveedor_id", "unidad", "placas", "tipo", "conductor"];
    protected $table = "proveedores_unidad";
    public $timestamps = false;
}
