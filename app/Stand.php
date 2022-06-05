<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stand extends Model
{
	protected $fillable = ['id','Nombre','almacene_id','condicion'];
     
    //
    public function almacen()
    {
    	return $this->belongsTo('App\Almacene');
    }
    public function nivel()
    {
    	return $this->hasMany('App\Nivele');
    }
}
