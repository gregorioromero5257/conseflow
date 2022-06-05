<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatCatUsoCfdi extends Model
{
    //
    protected $fillable = ['c_uso','descripcion','fisica','moral'];
    protected $table = 'sat_cat_usocfdi';
    public $timestamps = false;
}
