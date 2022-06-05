<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Extension extends Model
{
    //
      protected $fillable = ['nombre','departamento_id','condicion'];
      protected $table = 'extensiones';
      public $timestamps = false;
}
