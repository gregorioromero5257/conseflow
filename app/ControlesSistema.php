<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ControlesSistema extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'controles';
    public $timestamps = false;
}
