<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoCivilDBP extends Model
{
    //
      protected $connection = 'csct';
      protected $fillable = ['nombre'];
      protected $table = 'estados_civiles';
      public $timestamps = false;
}
