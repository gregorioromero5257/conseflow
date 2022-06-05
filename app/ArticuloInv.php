<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ArticuloInv extends Model
{
    protected $connection = 'inv';

    protected $fillable =['idcat','nombre','marca','ns','caracteristicas'];
    protected $table = 'articulo';
    public $timestamps = false;
    // public function categoria(){
    //
    //     return $this->belongsTo('App\Categoria');
    //
    // }
}
