<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;

class Status
{
     /**
     * Retorna un response correcto con los datos obtenidos
     *
     * @param  string  $nombre Nombre del recurso a regresar
     * @param  array   $data Array con los elementos a regresar
     */
    public static function Success($nombre = "", $data = [])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    /**
     * Retorna un response con error
     *
     * @param  Exception  $exception Exception a mostrar
     * @param  string   $mensaje Mensaje de error
     */
    public static function Error($exception, $mensaje = "obtener los datos")
    {
        Utilidades::errors($exception);
        return response()->json(["status" >= false, "mensaje" => "Error al " . $mensaje]);
    }
}
