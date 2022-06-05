<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Servicio;
use App\CalidadModels\ServicioSistema;
use App\CalidadModels\Sistema;
use App\Http\Controllers\Controller;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SistemasController extends Controller
{

    /**
     * Obtiene todos los procedimientos de sldadura
     */
    public function Obtener($id)
    {
        try
        {
            $sistemas = DB::table("calidad_sistemas as s")
                ->where("s.proyecto_id", $id)
                ->select("s.*")
                ->orderBy("s.nombre")
                ->get();

            return response()->json(["status" => true, "sistemas" => $sistemas]);
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
            $sistema = new Sistema();
            $sistema->fill($request->all());
            $sistema->save();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }

    public function AsignarServicio(Request $request)
    {
        try
        {
            $serv_sis = new ServicioSistema();
            $serv_sis->fill($request->all());
            $serv_sis->save();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }
}
