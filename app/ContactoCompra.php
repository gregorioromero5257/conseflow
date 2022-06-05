<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContactoCompra extends Model
{
    protected $fillable = ['nombre_contacto','telefono_oficina','telefono_movil','area','email','cliente_id'];
    protected $table = 'contacto_compra';
    public $timestamps = false;
}
