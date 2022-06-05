<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\CalibracionMaquina;
use App\CalidadModels\SoldadorProyecto;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\Soldadores;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SoldadoresProyectoController extends Controller
{

    /**
     * Obtiene los soldadores
     */
    public function Obtener($proyecto_id)
    {
        try
        {
            $soldadores = DB::table("calidad_soldador_proyecto as csp")
                ->join("calidad_proyectos as cp", "cp.id", "csp.proyecto_id")
                ->join("proyectos as p", "p.id", "cp.proyecto_id")
                ->join("certificaciones_procedimiento as cp2", "certificacion_procedimiento_id", "cp2.id")
                ->join("calidad_maquinas_soldar as cms", "cms.id", "csp.maquina_id")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp2.procedimiento_id")
                ->join("calidad_soldadores as cs", "cs.id", "cp2.soldador_id")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->select(
                    "csp.id",
                    "cs.clave",
                    "cps.descripcion as nombre_procedimiento",
                    "cps.id as procedimiento_id",
                    "cs.id as soldador_id",
                    "cp2.wpq",
                    "cms.id as maquina_id",
                    "cms.no_control",
                    DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre_soldador"),
                    DB::raw("concat(cs.clave,' - ',e.ap_paterno,' ',e.ap_materno,' ',e.nombre) as nombre_clave")
                )->where("csp.proyecto_id", $proyecto_id)
                ->get();
            return $this->Success("soldadores", $soldadores);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Muestra los detalles del soldador de proyecto ingresado
     */
    public function Detalles($soldador_id)
    {
        try
        {
            // Soldador
            $soldador = DB::table("calidad_soldador_proyecto as csp")
                ->join("certificaciones_procedimiento as cp", "cp.id", "csp.certificacion_procedimiento_id")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                ->join("calidad_soldadores as cs", "cs.id", "cp.soldador_id")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->where("csp.id", $soldador_id)
                ->select(
                    "cs.id as soldador_id",
                    "cp.wpq",
                    "cp.fecha_certificacion",
                    "cp.folio as folio_procedimiento",
                    "cp.id as procedimiento_id",
                    "cps.descripcion as nombre_procedimiento",
                    DB::raw("concat(cs.clave,' - ',e.ap_paterno,' ',e.ap_materno,' ',e.nombre) as nombre_clave"),
                    DB::raw("concat_ws(' ',cps.clave, cp.folio) as clave_folio")
                )->first();
            // Maquina
            $maquina = DB::table("calidad_soldador_proyecto as csp")
                ->join("calidad_calibracion_maquinas as ccm", "ccm.id", "csp.maquina_id")
                ->join("calidad_maquinas_soldar as cms", "cms.id", "ccm.maquina_id")
                ->where("csp.id", $soldador_id)
                ->select(
                    "ccm.no_certificado",
                    "cms.id as maquina_id",
                    "cms.no_control",
                    "cms.no_serie",
                    "cms.marca",
                    "cms.modelo",
                    "ccm.id as certificado_id",
                    "ccm.fecha as fecha_calibracion"
                )->first();
            return $this->Success("soldador", array_merge((array)$soldador, (array)$maquina));
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Obtiene todas las maquinas de soldar que tienen minimo una calibración registrada
     */
    public function ObtenerMaquinas()
    {
        try
        {
            $ids = DB::select("
            SELECT DISTINCT (maquina_id)
            from calidad_calibracion_maquinas ccm
            order by maquina_id");
            // Comprobar que exista una máquina registrada
            if (count($ids) == 0)
                return response()->json(["status" => false, "mensaje" => "Sin máquinas de soldar"]);
            $ids_aux = "id= " . $ids[0]->maquina_id;
            array_shift($ids);
            foreach ($ids as $id)
            {
                $ids_aux .= " or id= " . $id->maquina_id;
            }
            $maquinas = DB::select("SELECT * from calidad_maquinas_soldar cms
            where " . $ids_aux . " order by cms.no_control");
            return $this->Success("maquinas", $maquinas);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener las máquinas");
        }
    }

    /**
     * Obtiene los certificados de calibración de la maquina ingresada
     */
    public function ObtenerCertificados($maquina_id)
    {
        $certificados = CalibracionMaquina::where("maquina_id", $maquina_id)
            ->orderBy("no_certificado")->get();
        return $this->Success("certificados", $certificados);
    }

    /**
     * Obtiene los soldadores con alguna certificación
     */
    public function ObtenerSoldadores()
    {
        try
        {
            $ids = DB::select("SELECT DISTINCT (soldador_id) as s_id
            from certificaciones_procedimiento as cp");
            $ids_aux = " cs.id = 1 ";
            foreach ($ids as $id)
            {
                $ids_aux .= " or cs.id= " . $id->s_id;
            }
            $soldadores = DB::table("calidad_soldadores as cs")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->where("cs.estado", "=", "1")
                ->select(
                    "cs.*",
                    DB::raw("concat_ws(' ',cs.clave,'-',e.ap_paterno,e.ap_materno,e.nombre) as nombre_clave")
                )
                ->orderBy("cs.clave")
                ->whereRaw($ids_aux)
                ->get();
            return $this->Success("soldadores", $soldadores);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener los soldadores");
        }
    }

    /**
     * Obtiene las certificaciones del soldador ingresado
     */
    public function ObtenerCertificaciones($soldador_id)
    {
        try
        {
            $procedimientos = DB::table("certificaciones_procedimiento as cp")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                ->where("cp.soldador_id", $soldador_id)
                ->select(
                    "cp.*",
                    "cps.descripcion as nombre_procedimiento",
                    DB::raw("concat_ws(' ',cps.clave,cp.folio) as clave_folio")
                )->get();
            return $this->Success("procedimientos", $procedimientos);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Guarda la relación CrtificaciónProcedimiento, CertificadoMaquina y Proyecto
     */
    public function Guardar(Request $request)
    {
        try
        {
            $soldador = null;
            if ($request->id == null) // Nuevo
            {
                $soldador = new SoldadorProyecto($request->all());
                $soldador->save();
            }
            else
            {
                $soldador = SoldadorProyecto::find($request->id);
                $soldador->fill($request->all());
                $soldador->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "guardar soldador");
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
