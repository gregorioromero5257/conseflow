<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use App\Asistencia;
use App\Registro;
use Exception;

class ChecadorController extends Controller
{
    public function index(Request $request)
    {
        // DB::beginTransaction();
        try
        {
            //Datos
            $registros = json_decode($request->asistencias_json, true);


            // if (!$request->hasFile('ImagenesZip'))
            //     return response()->json(new Response(404, "Sin archivo de imágenes", null, false));

            // Guardar imágenes
            // $zip_imagenes = $request->file('ImagenesZip');

            foreach ($registros as $r)
            {
                if ($r["tipo"] == 1) // Entrada
                {
                    $registrado = Asistencia::where('empleado_id', $r["empleado_id"])
                        ->where('fecha', $r["fecha"])
                        ->first();

                    // Comprobar que no esté registrado
                    if ($registrado == null)
                    {
                        // Crear registro
                        $registro = new Registro();
                        $registro->hora_entrada = $r["entrada"];
                        $registro->hora_salida = "00:00:00";
                        $registro->pendiente = 1;
                        $registro->save();

                        // Crear asistencia
                        $asistencia = new Asistencia();
                        $asistencia->fecha = $r["fecha"];
                        $asistencia->registro_id = $registro->id;
                        $asistencia->empleado_id = $r["empleado_id"];
                        // Utilidades::auditar($asistencia, $asistencia->id, 2);
                        $asistencia->save();
                    }
                    else
                    {
                        // Ya tiene registro. Actualizar datos
                        $registro = Registro::find($registrado->registro_id);
                        $registro->hora_entrada = $r["entrada"];
                        $registro->save();
                    }
                }
                else // Salida
                {
                    $asistencia = Asistencia::where('empleado_id', $r["empleado_id"])
                        ->where('fecha', $r["fecha"])
                        ->first();
                    if ($asistencia != null)
                    {
                        // Obtener registro
                        $registro = Registro::find($asistencia->registro_id);
                        if ($r["salida_comida"] != null) $registro->hora_salida_comida = $r["salida_comida"];
                        if ($r["entrada_comida"] != null) $registro->hora_entrada_comida = $r["entrada_comida"];
                        if ($r["salida"]) $registro->hora_salida = $r["salida"];
                        $registro->save();
                    }
                    else
                    {
                        // Crear registro
                        $registro = new Registro();
                        $registro->hora_entrada = $r["entrada"];
                        if ($r["salida_comida"] != null) $registro->hora_salida_comida = $r["salida_comida"];
                        if ($r["entrada_comida"] != null) $registro->hora_entrada_comida = $r["entrada_comida"];
                        if ($r["salida"]) $registro->hora_salida = $r["salida"];
                        $registro->pendiente = 1;
                        $registro->save();

                        // Crear asistencia
                        $asistencia = new Asistencia();
                        $asistencia->fecha = $r["fecha"];
                        $asistencia->registro_id = $registro->id;
                        $asistencia->empleado_id = $r["empleado_id"];
                        // Utilidades::auditar($asistencia, $asistencia->id, 2);
                        $asistencia->save();
                    }
                }
            }
            return response()->json(new Response(200, 'OK', true, true));
        }
        catch (Exception $e)
        {
            // DB::rollback();
            return response()->json(new Response(500, 'Error:' . $e->getMessage(), null, false));
        }
    }
}

class Response
{
    public $StatusCode;
    public $Message;
    public $Data;
    public $IsSuccess;

    public function __construct($StatusCode, $Message, $Data, $IsSuccess)
    {
        $this->StatusCode = $StatusCode;
        $this->Message = $Message;
        $this->Data = $Data;
        $this->IsSuccess = $IsSuccess;
    }
}
