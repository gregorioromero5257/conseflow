<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PagosNoRecurrentes extends Model
{
    protected $fillable = ['proveedor_id','ordenes_comp_id','proyecto_id','monto','rango_dias','eventos','condicion','fecha_autorizado'];
    protected $table = 'pagos_no_recurrentes';
    public $timestamps = false;
}
