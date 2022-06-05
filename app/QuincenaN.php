<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuincenaN extends Model
{
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'nombre','puesto','rfc','nss','curp','sdi_imss','sd','ss','dl','desc','infonavit','nomina','viaticos_alimentos','viaticos',
      'total','semana','nomina_rh'

    ];
    protected $table = 'quincena_nomina';
    public $timestamps = false;

}
