<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Relacionados extends Model
{
    //
      protected $fillable = ['factura_id','uuid'];
      protected $table = 'facturas_relacionadas';
      public $timestamps = false;


}
