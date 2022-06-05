<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SemanaN extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'nombre','puesto','rfc','nss','curp','sdi_imss','sd','ss','dl','desc','infonavit','nomina','viaticos_alimentos',
      'total','semana','nomina_rh'

    ];
    protected $table = 'semana_nomina';
    public $timestamps = false;

}
