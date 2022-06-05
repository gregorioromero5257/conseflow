<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Servicio;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;

class ServiciosController extends Controller
{

    /**
     * Obtiene todos los procedimientos de sldadura
     */
    public function Obtener()
    {
        try
        {
            $servicios = Servicio::get();
            return response()->json(["status" => true, "servicios" => $servicios]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Crea o almacena un proyecto para calidad
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->nuevo == 1)
            {
                $servicio = new Servicio();
                $servicio->fill($request->all());
                $servicio->save();
            }
            else
            {
                $servicio = Servicio::find($request->id);
                $servicio->fill($request->all());
                $servicio->save();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }
}
