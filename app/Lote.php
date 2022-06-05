<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lote extends Model
{
    protected $fillable =['articulo_id', 'Cantidad', 'EntradaId', 'condicion'];
}
