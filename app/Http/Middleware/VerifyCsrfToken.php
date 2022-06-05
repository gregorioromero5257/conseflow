<?php

namespace App\Http\Middleware;

use App\Proveedor;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        //
        '/checador',
        // Lector -> Almacén
        "/api/almacen/ingresar",
        "/api/almacen/guardar",
        "/api/almacen/registrarentrada",
        "/api/almacen/salidas/registra",
        "/api/almacen/salidas/resguardo",
        "/api/almacen/salidas/actualizarcodigo",

        // Proveedores
        "/api/proveedores/ingresar",
        "/api/proveedores/subirfactura",
        "/api/proveedores/obtenerfacturas",
        "/api/proveedores/descargar",
        "/api/proveedores/actualizar",
        "/api/proveedores/aceptaracuerdo",
        "/api/proveedores/registrarproveedor",
        "/api/proveedores/cambiarcontra",
        
        // Checador v2
        "/guardar/reg/checador",
        "buscar/proyecto/sabana/qr",
        "guardar/reg/control/tiempo/qr",

        // Cargar unidades Calidad
        "/calidad/recepciones/cargarunidades",
    ];
}
