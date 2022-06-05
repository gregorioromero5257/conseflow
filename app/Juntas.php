<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Juntas extends Model
{
    //
    protected $table = 'concentrado_spools';
    protected $fillable = ['juntas','diametro','sistema','habilitadas','fecha_uno','soldadas','fecha_dos','w'];
    public $timestamps = false;
}
