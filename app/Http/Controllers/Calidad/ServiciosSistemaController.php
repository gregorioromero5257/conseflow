<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\ServicioSistema;
use App\CalidadModels\Spool;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ServiciosSistemaController extends Controller
{

    /**
     * Obtiene todos los servicios del proyecto ingresado
     */
    public function Obtener($id)
    {
        try
        {
            $sistemas = DB::table("calidad_sistemas as s")
                ->join("servicios_sistema as sds", "sds.sistema_id", "s.id")
                ->join("calidad_servicios as cs", "cs.id", "sds.servicio_id")
                ->where("s.proyecto_id", $id)
                ->select(
                    "s.id as s_id",
                    "s.nombre as s_nombre",
                    "s.espeificaciones_material as s_material",
                    "s.tag",
                    "sds.id as sds_id",
                    "cs.id as serv_id",
                    "cs.nombre as serv_nombre",
                    "sds.plano as plano"
                )
                ->get();

            return response()->json(["status" => true, "servicios_sistemas" => $sistemas]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Crea o almacena un proyecto para calidad
     */

    public function AsignarServicio(Request $request)
    {
        try
        {
            if ($request->nuevo == 1)
            {
                $serv_sis = new ServicioSistema();
                $serv_sis->fill($request->all());
                $serv_sis->save();
            }
            else
            {
                $sds = ServicioSistema::find($request->id);
                $sds->fill($request->all());
                $sds->save();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mesaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene los servicios del sistema ingresado
     */
    public function ObtenerServicios($sistema_id)
    {
        try
        {
            $servicios = DB::table("servicios_sistema as ss")
                ->join("calidad_servicios as cs", "cs.id", "ss.servicio_id")
                ->where("ss.sistema_id", $sistema_id)
                ->select(
                    "ss.id as ss_id",
                    "cs.id as id",
                    "cs.nombre as nombre"
                )->get();
            return $this->Success("servicios", $servicios);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los sistemas");
        }
    }

    /**
     * Obtiene los servicios del sistema ingresado
     */
    public function ObtenerSistemas($ss_id)
    {
        try
        {
            $sistemas = DB::table("calidad_sistemas as cs")
                ->where("proyecto_id", $ss_id)
                ->select(
                    "id",
                    "nombre"
                )->get();
            return $this->Success("sistemas", $sistemas);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los servicios");
        }
    }

    /**
     * Muestra los spools del SS ingresado
     */
    public function VerSpools($ss_id)
    {
        try
        {
            $spools =   DB::table("calidad_spools as cs")
                ->where("cs.servicio_sistema_id", $ss_id)->get();
            return $this->Success("spools", $spools);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los spools");
        }
    }

    /**
     * Registra o guarda un spool
     */
    public function GuardarSpool(Request $request)
    {
        try
        {
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
            return $this->Error($e, "guardar el spool");
        }
    }

    /**
     * Obtiene los servicios y sistemas del proyecto ingresado
     */
    public function ObtenerServiciosSistema($p_id)
    {
        try
        {
            $sistemas = DB::table("servicios_sistema as ss")
                ->join("calidad_sistemas as cs1", "cs1.id", "ss.sistema_id")
                ->join("calidad_servicios as cs2", "cs2.id", "ss.servicio_id")
                ->join("calidad_proyectos as cp", "cp.id", "cs1.proyecto_id")
                ->join("proyectos as p", "p.id", "cp.proyecto_id")
                ->where("cp.id", $p_id)
                ->select(
                    DB::raw("concat(cs1.nombre,' - ',cs2.nombre) as nombre"),
                    "ss.id as ss_id",
                    "ss.plano",
                    "cs1.id as sistema_id",
                    "cs2.id as servicio_id",
                    "p.nombre as proyecto_nombre",
                    "cs1.nombre as sistema_nombre",
                    "cs1.espeificaciones_material",
                    "cp.id as p_id"
                )->get();
            return $this->Success("sistemas", $sistemas);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los sistemas");
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
