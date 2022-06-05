<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Grados extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'grados';
    public $timestamps = false;
}
