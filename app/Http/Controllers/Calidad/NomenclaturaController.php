<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Nomenclatura;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NomenclaturaController extends Controller
{

    /**
     * Registra una nueva nomenclatura
     */
    public function Registrar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $nomen = new Nomenclatura($request->all());
                $nomen->save();
            }
            else
            {
                $nomen = Nomenclatura::find($request->id);
                $nomen->fill($request->all());
                $nomen->update();
            }

            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "registrar la nomenclatura");
        }
    }

    /**
     * Obtiene todas las nomenclaturas registradas
     */
    public function Obtener()
    {
        try
        {
            $nomens = DB::table("calidad_nomenclaturas as cm")
            ->select("cm.*",DB::raw("concat_ws(' - ',cm.clave,cm.nombre) as aux_nombre"))
            ->orderBy("cm.clave")->get();
            return $this->Success("nomenclaturas", $nomens);
        }
        catch (Exception $e)
        {
            return response()->json(["status"=> false,"mensaje"=> "Error al obtener los datos"]);
        }
    }

    public function Success($nombre = "", $data = [])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    public function Error($e, $mensaje = "obtener los datos")
    {
        if ($e != null)
            Utilidades::errors($e);
        return response()->json(["status" => false, "mensaje" => "Error al " . $mensaje]);
    }
}
