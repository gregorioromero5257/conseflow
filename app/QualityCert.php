<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QualityCert extends Model
{
  protected $fillable = ['adjunto', 'responsable', 'folio_proveedor', 'partidaentrada_id', 'documento_id', 'estado_documento', 'empleado_responsable_id'];
 protected $table = 'quality_certificates';
 public $timestamps = false;
}
