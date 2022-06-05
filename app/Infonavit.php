<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Infonavit extends Model
{
    //
    //
    protected $connection = 'nomina';

    protected $fillable = [
      'nss','nombre','dias','valor','importe','n','tipo','periodo','fecha_i','fecha_f'
    ];
    protected $table = 'infonavit';
    public $timestamps = false;
}
