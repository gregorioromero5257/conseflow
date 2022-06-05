<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenRHOC extends Model
{
    //
    protected $fillable = ['imagen','req_com_id'];
    protected $table = 'partidas_oc_imagenes';
    public $timestamps = false;

}
