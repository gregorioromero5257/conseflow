<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Procedimiento;
use App\CalidadModels\Proyecto;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProcedimientosController extends Controller
{

    /**
     * Obtiene todos los procedimientos de soldadura
     */
    public function Obtener()
    {
        try
        {
            $procedimietos = DB::table("calidad_proced_soldadura as cps")
            ->select("cps.*",DB::raw("CONCAT_WS(' -  ',cps.clave,descripcion,cps.wps,cps.pqr ) as nombre_aux"))
            ->get();
            return response()->json(["status" => true, "procedimietos" => $procedimietos]);
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
                $procedimieto = new Procedimiento();
                $procedimieto->fill($request->all());
                $procedimieto->save();
            }
            else
            {
                $procedimieto = Procedimiento::find($request->id);
                $procedimieto->fill($request->all());
                $procedimieto->save();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }
}
