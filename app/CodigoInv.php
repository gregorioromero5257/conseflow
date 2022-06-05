<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodigoInv extends Model
{
    protected $connection = 'inv';

    protected $fillable =['codigo','idreg','estado','img'];
    protected $table = 'codigo';
    public $timestamps = false;
    // public function categoria(){
    //
    //     return $this->belongsTo('App\Categoria');
    //
    // }
}
