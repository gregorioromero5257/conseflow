<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TipoAdquisicion extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'tipo_adquisicion';
    public $timestamps = false;

}
