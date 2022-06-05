<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacene extends Model
{
    //
    protected $fillable = ['id','Nombre','condicion'];

    public function stand()
    {
    	return $this->hasMany('App\Stand');
    }
}
