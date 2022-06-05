<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Adicionales extends Model
{
    //
      protected $fillable = ['req_has_comp','cantidad'];
      protected $table = 'adicionales';
      public $timestamps = false;
}
