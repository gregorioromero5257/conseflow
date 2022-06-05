<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SolicitudViaticosDBP extends Model
{
  protected $connection = 'csct';
  protected $fillable = [
  'fecha_solicitud',
  'fecha_pago',
  'folio',
  'proyecto_id',
  'origen_destino',
  'fecha_salida',
  'hora_estimada_salida',
  'fecha_operacion',
  'fecha_retorno',
  'empleado_elabora_id',
  'empleado_revisa_id',
  'empleado_autoriza_id',
  'total_personas',
  'empleado_supervisor_id',
  'estado','empleado_user_id','tipo','origen_destino_destino','eliminado','timbrado'];
  protected $table = 'solicitud_viaticos';
  public $timestamps = false;
    //
}
