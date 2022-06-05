<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Beneficiario extends Model
{
    protected $fillable = ['id','nombre', 'telefono', 'tel_celular', 'parentesco', 'porcentaje', 'condicion', 'empleado_id'];
    protected $table = 'beneficiarios';
    public $timestamps = false;
}