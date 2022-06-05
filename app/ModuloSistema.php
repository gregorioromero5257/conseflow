<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ModuloSistema extends Model
{
    protected $fillable = ['nombre','color','icono', 'submenu', 'condicion', 'page'];
    protected $table = 'modulos';
    public $timestamps = false;
}
