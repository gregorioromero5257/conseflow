<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class RegistroResguardo extends Model
{
    protected $fillable = ['tipo_resguardo_id','salida_id','tipo_salida_id','responsable_id','articulo_id','cantidad', 'fecha_entrega', 'fecha_devolucion'];
    protected $table = 'registro_resguardo';
    public $timestamps = false;
}
