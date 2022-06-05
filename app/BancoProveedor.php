<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BancoProveedor extends Model
{
    protected $fillable = ['id', 'banco','cuenta','clabe','proveedor_id'];
    protected $table = 'bancos_proveedores';
    public $timestamps = false;
}
