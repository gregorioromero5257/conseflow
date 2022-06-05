<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TecnicaExaminacionVisual extends Model
{
    //
    protected $fillable = ['nombre'];
    protected $table = 'tecnica_examinacion_visual';
    public $timestamps = false;
}
