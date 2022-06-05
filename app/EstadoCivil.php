<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCivil extends Model
{
    //
      protected $fillable = ['nombre'];
      protected $table = 'estados_civiles';
      public $timestamps = false;
}
