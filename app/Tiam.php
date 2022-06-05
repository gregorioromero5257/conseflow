<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tiam extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'tiam';
    public $timestamps = false;
}
