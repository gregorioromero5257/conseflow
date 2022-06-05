<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LoteDBP extends Model
{
    protected $connection = 'csct';
    protected $table = 'lotes';
    protected $fillable =['articulo_id', 'Cantidad', 'EntradaId', 'condicion'];
}
