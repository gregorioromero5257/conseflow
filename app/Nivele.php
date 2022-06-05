<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nivele extends Model
{
	protected $fillable = ['id','Nombre','stand_id'];

	public function categoria()
	{
	
		return $this->hasMany('App\Categoria');	
	
	}
    
    public function stand()
    {
    
    	return $this->belongsTo('App\Stand');
    }
}
