<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportesRegistros extends Model
{
    //
    protected $fillable = ['reportes_encabezados_id','total','semana','rango'];
    protected $table = 'reportes_registros';
    public $timestamps = false;
}
