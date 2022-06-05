<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Identificacion extends Model
{
    //
      protected $fillable = ['curp','rfc','cartilla','nss_imms','empleado_id'];
      protected $table = 'identificaciones';
      public $timestamps = false;
}
