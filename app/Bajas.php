<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Bajas extends Model
{
    protected $fillable = ['format_renuncia', 'finiquito', 'fecha_baja', 'observaciones', 'contrato_id'];
    protected $table = 'bajas';
    public $timestamps = false;
}
