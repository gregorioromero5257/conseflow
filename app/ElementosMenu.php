<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ElementosMenu extends Model
{
    //
    protected $fillable = ['clase','name','page','modulo_id'];
    public $timestamps = false;
}

