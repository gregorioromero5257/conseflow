<?php

namespace App\Http\Controllers\Calidad;;

use App\CalidadModels\NomenclaturaTrazabilidad;
use App\CalidadModels\TrazabilidadJuntas;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade as PDF;

class TrazabilidadController extends Controller
{
    /**
     * Obtiene las inspecciones de trazabilidad del proyecto ingresado
     */
    public function Obtener($proyecto_id)
    {
        try
        {
            $inspecciones = DB::table("calidad_trazabilidad_juntas as ctj")
                ->join("servicios_sistema as ss", "ss.id", "ctj.servicios_sistema_id")
                ->join("calidad_servicios as cs", "cs.id", "ss.servicio_id")
                ->join("calidad_sistemas as cs2", "cs2.id", "ss.sistema_id")
                ->join("calidad_proyectos as cp", "cp.id", "cs2.proyecto_id")
                ->where("cp.id", $proyecto_id)
                ->select(
                    "ctj.*",
                    "cp.nombre as cp_nombre",
                    "cp.id as cp_id",
                    DB::raw("CONCAT_WS(' - ',cs2.nombre,cs.nombre)  as nombre")
                )
                ->get();

            return $this->Success("inspecciones", $inspecciones);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener las inspecciones");
        }
    }

    /**
     * Registra una nueva trazabilidad
     */
    public function Registrar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $trazab = new TrazabilidadJuntas();
                $trazab->fill($request->all());
                $trazab->save();
            }
            else
            {
                $trazab = TrazabilidadJuntas::find($request->id);
                $trazab->folio = $request->folio;
                $trazab->identificacion = $request->identificacion;
                $trazab->update();
            }
            return $this->Success("");
        }
        catch (Exception $e)
        {
            return $this->Error($e, "guardar la inspección");
        }
    }

    /**
     * Registra la nomenclatura en la inspección ingresada
     */
    public function RegistrarNomen(Request $request)
    {
        try
        {
            $nomen = new NomenclaturaTrazabilidad($request->all());
            $nomen->save();
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "registar la nomenclatura");
        }
    }


    /**
     * Obtiene las nomenclaturas de la inspección ingresada
     */
    function ObtenerNomens($inspeccion_id)
    {
        try
        {
            $nomens = $this->AuxObtenerNomens($inspeccion_id);
            if (!$nomens)
                return response()->json(["status" => false, "mensaje" => "Error 2 al obtener las nomenclaturas"]);
            return $this->Success("nomenclaturas", $nomens);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "obtener las nomenclaturas");
        }
    }

    /**
     * Obtiene las nomenclaturas de la inspección ingresada
     */
    private function AuxObtenerNomens($inspeccion_id)
    {
        try
        {
            $nomens = DB::table("calidad_trazabilidad_juntas as ctj")
                ->join("calidad_nomens_trazab as cnt", "cnt.inpec_trazab_id", "ctj.id")
                ->join("calidad_nomenclaturas as cn", "cn.id", "cnt.nomen_id")
                ->where("inpec_trazab_id", $inspeccion_id)
                ->orderBy("cn.clave")
                ->select("cn.*")
                ->get();
            return $nomens;
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return false;
        }
    }

    /**
     * Genera el reporte en pdf de la inspección de trzabilidad
     */
    public function Reporte($inspeccion_id)
    {
        try
        {
            // Datos de la inspección
            $inspec = DB::table("calidad_trazabilidad_juntas as ctj")
                ->join("servicios_sistema as ss", "ss.id", "ctj.servicios_sistema_id")
                ->join("calidad_sistemas as cs", "cs.id", "ss.sistema_id")
                ->join("calidad_proyectos as cp", "cp.id", "cs.proyecto_id")
                ->where("ctj.id", $inspeccion_id)
                ->select(
                    "ctj.*",
                    "cs.nombre as elemento",
                    "cp.nombre"
                )
                ->first();

            // Obtener juntas
            $juntas = DB::table("servicios_sistema as ss")
                ->join("calidad_sistemas as sis", "sis.id", "ss.sistema_id")
                ->join("calidad_proyectos as p", "p.id", "sis.proyecto_id")
                ->join("calidad_spools as sp", "sp.servicio_sistema_id", "ss.id")
                ->join("calidad_juntas as j", "j.spool_id", "sp.id")
                ->join("calidad_juntas_materiales as cjm1", "cjm1.id", "j.material_uno_id")
                ->join("calidad_juntas_materiales as cjm2", "cjm2.id", "j.material_dos_id")
                ->join("coladas_inspeccion as ci1", "ci1.id", "cjm1.colada_inspeccion_id")
                ->join("coladas_inspeccion as ci2", "ci2.id", "cjm2.colada_inspeccion_id")
                ->join("inspecciones_calidad as ic1", "ic1.id", "ci1.inspeccion_id")
                ->join("articulos as a1", "ic1.articulo_id", "a1.id")
                ->join("inspecciones_calidad as ic2", "ic2.id", "ci2.inspeccion_id")
                ->join("articulos as a2", "ic2.articulo_id", "a2.id")
                ->join("calidad_soldador_proyecto as csp", "csp.id", "j.soldador_proyecto_id")
                ->join("certificaciones_procedimiento as cp", "cp.id", "csp.certificacion_procedimiento_id")
                ->join("calidad_soldadores as cs", "cs.id", "cp.soldador_id")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                ->join("calidad_calibracion_maquinas as ccm", "ccm.id", "csp.maquina_id")
                ->join("calidad_maquinas_soldar as cms", "cms.id", "ccm.maquina_id")
                ->join("calidad_inspecciones_visual as civ", "civ.servicios_sistema_id", "ss.id")
                ->join("empleados as ei", "ei.id", "civ.empleado_inspecciona_id")
                ->where("ss.id", $inspec->servicios_sistema_id)
                ->select(
                    "ss.plano",
                    "cps.wps",
                    "cps.pqr",
                    "cp.wpq",
                    "e.nombre as soldador",
                    "cs.clave as soldador_clave",
                    "ccm.no_certificado as cert_maquina",
                    "cps.clave as proced_clave",
                    "j.nombre as j_no",
                    "j.diametro",
                    "cps.material_aporte",
                    "cps.tipo_preparacion",
                    "sis.espeificaciones_material",
                    "cjm1.nombre_corto as m_1",
                    "ci1.colada as col_1",
                    "cjm2.nombre_corto as m_2",
                    "ci2.colada as col_2",
                    "civ.folio as vt_folio",
                    "civ.fecha as vt_fecha",
                    "civ.clave_inspector",
                    "ei.nombre as inspector",
                )->get();

            // Nomenclaturas
            $nomens = $this->AuxObtenerNomens($inspeccion_id);
            $array_nomens = array_chunk($nomens->toArray(), 3);

            // Generar reporte
            $pdf = PDF::loadView('pdf.inspecciontrazabilidad', compact("inspec", "juntas", "array_nomens"));
            $pdf->setPaper('ledger', 'portrait');
            error_reporting(E_ALL ^ E_DEPRECATED);
            $pdf->getDomPDF()->set_option("enable_php", true);
            return $pdf->stream($inspec->folio);
        }
        catch (Exception $e)
        {
            // Utilidades::errors($e);
            dd($e);
            // return view("errors.500");
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
