<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RcesES extends Model
{
    //
      protected $fillable = ['rhoc_id'];
      protected $table = 'recs_entradas_salidas';
      public $timestamps = false;
}
