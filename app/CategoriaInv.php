<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CategoriaInv extends Model
{
    protected $connection = 'inv';

    protected $fillable =['nombre'];
    protected $table = 'categoria';
    public $timestamps = false;
    // public function categoria(){
    //
    //     return $this->belongsTo('App\Categoria');
    //
    // }
}
