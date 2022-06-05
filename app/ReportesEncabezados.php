<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportesEncabezados extends Model
{
    //
    protected $fillable = ['tipo','proyecto'];
    protected $table = 'reportes_encabezados';
    public $timestamps = false;
}
