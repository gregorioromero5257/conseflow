<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PartidasReDBP extends Model
{
   protected $connection = 'csct';
    protected $fillable =['requisicione_id','articulo_id','servicio_id','peso','unidad_peso','cantidad', 'equivalente','fecha_requerido','comentario','cantidad_compra','cantidad_almacen','condicion','pda','produccion','vehiculo_id'];
    protected $table = 'partidas_requisiciones';
    public $timestamps = false;


}
