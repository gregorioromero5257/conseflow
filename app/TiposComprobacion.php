<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class TiposComprobacion extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'catalogo_tipo_viaticos_gastosmenores';
    public $timestamps =false;
}
