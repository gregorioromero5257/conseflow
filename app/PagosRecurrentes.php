<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagosRecurrentes extends Model
{
    protected $fillable = ['nombre','fecha_inicial','rango_dias','eventos','proveedor_id','proyecto_id','ordenes_comp_id'];
    protected $table = 'pagos_recurrentes';
    public $timestamps = false;
}
