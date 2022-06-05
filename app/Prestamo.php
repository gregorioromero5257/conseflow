<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prestamo extends Model
{
    protected $fillable = ['cantidad','fecha','numero_pago','pagado','observaciones','adjunto','empleado_id'];
    protected $table = 'prestamos';
    public $timestamps = false;
}
