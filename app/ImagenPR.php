<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ImagenPR extends Model
{
  protected $fillable = ['imagen','requisicione_id','articulo_id'];
  protected $table = 'partidas_req_imagenes';
  public $timestamps = false;
    //
}
