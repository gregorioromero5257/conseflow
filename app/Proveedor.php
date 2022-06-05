<?php

namespace App;

use Illuminate\Database\Eloquent\Model;


class Proveedor extends Model

{

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
        'tipo',
        'passw',
        'condicion_aceptado',
        "regimen_fiscal",
        "portal_correo",
        "portal_existe"
        ];
    protected $table = 'proveedores';
    public $timestamps = false;

}
