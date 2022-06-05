<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroInv extends Model
{
    protected $connection = 'inv';

    protected $fillable =['idart','idubi','idedo','foto1','foto2','comentario'];
    protected $table = 'registro';
    public $timestamps = false;
    // public function categoria(){
    //
    //     return $this->belongsTo('App\Categoria');
    //
    // }
}
