<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatCatTipoRelacion extends Model
{
    //
    protected $fillable = ['c_tiporelacion','descripcion'];
    protected $table = 'sat_cat_tiporelacion';
    public $timestamps = false;
}
