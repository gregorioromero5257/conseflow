<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoRequisicion extends Model
{
    protected $fillable = ['nombre'];
    protected $table = 'estado_requisiciones';
    public $timestamps = false;

}
