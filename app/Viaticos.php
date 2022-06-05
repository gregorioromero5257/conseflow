<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Viaticos extends Model
{
  protected $fillable = ['solicitud_viáticos_id', 'gastos_comprobados_deducibles',
                         'gastos_comprobados_no_deducibles', 'importe_TEC', 'importe_EC','observaciones','pda'];
  protected $table = 'viaticos';
  public $timestamps = false;
}
