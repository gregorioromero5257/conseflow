<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SatCatTipoOtroPago extends Model
{
    //
    protected $fillable = ['c_tipootropago','descripcion','fecha_i_v','fecha_f_v'];
    protected $table = 'sat_cat_tipo_otro_pago';
    public $timestamps = false;
}
