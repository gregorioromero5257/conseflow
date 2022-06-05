<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable =['id','Nombre','Codigo','Descripcion','Marca','Unidad','Comentarios','Minimo','Maximo','grupo_id','ficha_tecnica','fotografia','calidad_id','codigo_producto','centro_costo_id','codigo_producto_sat',"um_id"];

    public function categoria(){

        return $this->belongsTo('App\Categoria');

    }
}
