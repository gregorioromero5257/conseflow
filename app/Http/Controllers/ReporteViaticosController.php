<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;
use App\ViaticoReporte;
use Exception;
use Illuminate\Http\Request;

class ReporteViaticosController extends Controller
{

    public function Obtener()
    {
        try
        {
            $viaticos=ViaticoReporte::get();;
            return $this->Success("viaticos",$viaticos);
        }
        catch (Exception $e)
        {
            return $this->Error($e,"obtener los registros");
        }
    }

    public function Registrar(Request $request)
    {
        try
        {
            if ($request->id == null || $request->id == "null") // nuevo
            {
                $viatico = new ViaticoReporte($request->all());
                $viatico->save();
            }
            else // Actu
            {
                $viatico = ViaticoReporte::find($request->id);
                $viatico->fill($request->all());
                $viatico->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e,"registrat viatico");
        }
    }


    public function Success($nombre = "", $data = [])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    public function Error($e, $mensaje = "Error al obtener los datos")
    {
        Utilidades::errors($e);
        return response()->json(["status" => false, "mensaje" => $mensaje]);
    }
}
