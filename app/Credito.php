<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Credito extends Model

{

    protected $fillable = ['proveedor_id','monto_debe','monto_disponible','monto_credito'];
    protected $table = 'credito';
    public $timestamps = false;

}
