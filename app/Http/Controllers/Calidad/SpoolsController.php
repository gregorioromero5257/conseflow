<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\Spool;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;

class SpoolsController extends Controller
{
    /**
     * Obtiene todos los spool del ss indicado
     */
    public function Obtener($ss_id)
    {
        try
        {
            $spools=Spool::where("servicio_sistema_id",$ss_id)->orderBy("no")->get();
            return $this->Success("spools",$spools);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los spools");
        }
    }

    /**
     * Guarda el spools con los datos ingresados
     */
    public function Guardar(Request $request)
    {
        try
        {
            $spool = null;
            if ($request->id == null)
            {
                $spool = new Spool($request->all());
                $spool->save();
            }
            else
            {
                $spool = Spool::find($request->id);
                $spool->no = $request->no;
                $spool->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "guardar el spools");
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
