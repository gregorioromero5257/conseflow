<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class ProveedorDBP extends Model

{
  protected $connection = 'csct';

    protected $fillable = ['nombre','razon_social','direccion','banco_transferencia','numero_cuenta','clabe','dia_credito','limite_credito','condicion',
        'categoria',
        'condicion_pago',
        'giro',
        'rfc',
        'ciudad',
        'estado',
        'contacto',
        'telefono',
        'correo',
        'pagina',
        'descripcion',
        'tipo_moneda',
        'tipo_cambio',
        ];
    protected $table = 'proveedores';
    public $timestamps = false;

}
