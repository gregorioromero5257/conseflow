<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    protected $fillable = ['nombre','proyecto_id', 'condicion'];

    public function lotes()
    {
        return $this->hasMany('App\Lote');
    }

}
