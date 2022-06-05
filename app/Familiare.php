<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Familiare extends Model
{
    //
      protected $fillable = ['nombre','parentesco','edad','vive','empleado_id','condicion'];
      protected $table = 'datos_familiares';
      public $timestamps = false;
}
