<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BajasDBP extends Model
{
    protected $connection = 'csct';

    protected $fillable = ['format_renuncia', 'finiquito', 'fecha_baja', 'observaciones', 'contrato_id'];
    protected $table = 'bajas';
    public $timestamps = false;
}
