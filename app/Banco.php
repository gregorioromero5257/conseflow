<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Banco extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'catalogo_bancos';
    public $timestamps = false;
}
