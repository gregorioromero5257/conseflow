<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubsidioEmpleo extends Model
{
    //
      protected $fillable = ['limite_inferior','limite_superior','cuota_fija'];
      protected $table = 'subsidio_empleo';
      public $timestamps = false;
}
