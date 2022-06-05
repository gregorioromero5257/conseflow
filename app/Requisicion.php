<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Requisicion extends Model
{
    //
      protected $fillable = ['folio','area_solicita_id','formato_requisiciones','fecha_solicitud','descripcion_uso','tipo_compra','proyecto_id','estado_id','solicita_empleado_id','autoriza_empleado_id','valida_empleado_id','recibe_empleado_id','almacen_empleado_id','condicion','pendiente'];
      protected $table = 'requisiciones';
      public $timestamps = false;

      public function compras()
      {
          return $this->hasMany('App\Compras');
      }
}
