<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoPercepciones extends Model
{
    protected $fillable = ['nombre','valor'];
    protected $table = 'tipo_percepciones';
    public $timestamps = false;
}
