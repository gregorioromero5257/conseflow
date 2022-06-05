<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticuloDBP extends Model
{
    protected $connection = 'csct';

    protected $fillable =['id','Nombre','Codigo','Descripcion','Marca','Unidad','Comentarios','Minimo','Maximo','grupo_id','ficha_tecnica','fotografia','calidad_id','codigo_producto','centro_costo_id'];
    protected $table = 'articulos';
    public $timestamps = false;
    // public function categoria(){
    //
    //     return $this->belongsTo('App\Categoria');
    //
    // }
}
