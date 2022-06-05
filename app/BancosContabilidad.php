<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancosContabilidad extends Model
{
    protected $fillable = ['catalogo_banco_id','numero_cuenta','numero_clave','total'];
    protected $table = 'bancos_contabilidad';
    public $timestamps = false;
}
