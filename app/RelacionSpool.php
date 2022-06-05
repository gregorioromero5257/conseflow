<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RelacionSpool extends Model
{
    //
    protected $table = 'relacion_spool';
    protected $fillable = ['proyecto','sistema','servicio','spool'];
    public $timestamps = false;
}
