<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expediente extends Model
{
    protected $fillable =['empleado_id', 'folio', 'solicitud'];
    public $timestamps = false;

}
