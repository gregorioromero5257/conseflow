<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Dia extends Model
{
    //
      protected $fillable = ['fecha','dia_id','registro_id'];
      protected $table = 'dias';
      public $timestamps = false;
}
