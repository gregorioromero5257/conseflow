<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Iluminacion extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'iluminacion';
    public $timestamps = false;
}
