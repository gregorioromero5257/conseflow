<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Inventario extends Model
{
    protected $table = 'inventario_almacen';
    protected $fillable =
    [
        "id",
        "codigo",
        "nombre",
        "descripcion",
        "cantidad",
        "marca",
        "um",
        "um_id",
        "categoria",
        "categoria_id",
        "grupo",
        "grupo_id",
        "proyecto",
        "proyecto_id",
        "tipo_resguardo",
        "tipo_resguardo_id",
        "almacen",
        "almacen_id",
        "stand",
        "stand_id",
        "nivel",
        "nivel_id"
    ];
    public $timestamps = false;
}
