<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoDeducciones extends Model
{
    protected $fillable = ['nombre','valor'];
    protected $table = 'tipo_deducciones';
    public $timestamps = false;
}
