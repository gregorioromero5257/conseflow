<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class PermisoUser extends Model
{
    protected $fillable = ['modulo_id','elementos_menu_id','elementos_submenu_id','user_id','C','R','U','D'];
    protected $table = 'permisos';
    public $timestamps = false;
}
