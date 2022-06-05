<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\InspeccionVisual;
use App\CalidadModels\NomenclaturaVisual;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;

class InspeccionVisualController  extends Controller
{

    /**
     * Obtiene las inspecciones del proyecto ingresado
     */

    public function ObtenerInspeccciones($p_id)
    {
        $inspecciones = DB::table("calidad_inspecciones_visual as civ")
            ->join("empleados as e1", "e1.id", "civ.empleado_inspecciona_id")
            ->join("empleados as e2", "e2.id", "civ.empleado_supervisa_id")
            ->join("calidad_proced_soldadura as cps", "cps.id", "civ.procedimiento_id")
            ->where("civ.proyecto_id", $p_id)
            ->select(
                "civ.id as civ_id",
                "civ.folio",
                "cps.clave",
                DB::raw("concat_ws(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as inspecciona"),
                DB::raw("concat_ws(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as supervisa"),
                "civ.aprobado",
                "civ.proyecto_id"
            )->get();
        return $this->Success("inspecciones", $inspecciones);
    }
    /**
     * Obtiene los detalles generales de la inspección
     */
    public function ObtenerDetalles($id)
    {
        $inspeccion = $this->DatosInspeccion($id);
        return $this->Success("inspeccion", $inspeccion);
    }

    /**
     * Obtiene las nomens de la inspección ingresada
     */
    public function ObtenerNomen($inspec_id)
    {
        $nomens = DB::table("calidad_nomens_visual as cnv")
            ->join("calidad_nomenclaturas as cn", "cn.id", "cnv.nomen_id")
            ->select("clave", "nombre")
            ->where("inspec_visual_id", $inspec_id)->get();
        return $this->Success("nomenclaturas", $nomens);
    }

    private function DatosInspeccion($id)
    {
        $inspeccion = DB::table("calidad_inspecciones_visual as civ")
            ->join("calidad_proced_soldadura as cps", "cps.id", "civ.procedimiento_id")
            ->join("servicios_sistema as ss", "ss.id", "civ.servicios_sistema_id")
            ->join("calidad_sistemas as cs", "cs.id", "ss.sistema_id")
            ->join("calidad_servicios as cs2", "cs2.id", "ss.servicio_id")
            ->join("empleados as e1", "e1.id", "civ.empleado_inspecciona_id")
            ->join("empleados as e2", "e2.id", "civ.empleado_supervisa_id")
            ->join("calidad_proyectos as cp", "cp.id", "civ.proyecto_id")
            ->join("proyectos as p", "p.id", "cp.proyecto_id")
            ->where("civ.id", $id)
            ->select(
                "civ.*",
                DB::raw("concat_ws(' - ',cps.clave, cps.descripcion) as procedimiento_nombre"),
                DB::raw("concat_ws(' - ',cs.nombre,cs2.nombre) as ss_nombre"),
                DB::raw("concat_ws(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as empleado_inspecciona"),
                DB::raw("concat_ws(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as empleado_supervisa"),
                "ss.id as ss_id",
                "cps.wps",
                "cps.pqr",
                "cps.clave as cps_clave",
                "cps.id as cps_id",
                "cps.descripcion as cps_descripcion",
                "cps.tipo_preparacion as cps_tipo_preparacion",
                "cps.material_aporte as cps_material_aporte",
                "ss.plano",
                "cs.nombre as sistema_nombre",
                "cs.id as cs_id",
                "cs.espeificaciones_material",
                "cs2.id as cs2_id",
                "p.nombre as p_nombre",
                "cp.nombre as proyecto_nombre"
            )
            ->first();
        return $inspeccion;
    }
    /**
     * Guarda la inspeccion visual
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $inspeccion = new InspeccionVisual($request->all());;
                $inspeccion->save();
            }
            else
            {
                $inspeccion = InspeccionVisual::find($request->id);
                $inspeccion->fill($request->all());
                $inspeccion->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "guardar la inspeccion");
        }
    }

    /**
     * Genera el reporte de la inspección ingresada
     */
    public function GenerarReporte($id_inspeccion)
    {
        try
        {
            // obtener datos de inspección
            $inspeccion = $this->DatosInspeccion($id_inspeccion);

            // obtener juntas del servicio_sistema seleccionado
            $juntas = DB::table("servicios_sistema as ss")
                ->join("calidad_spools as cs", "cs.servicio_sistema_id", "ss.id")
                ->join("calidad_juntas as cj", "cj.spool_id", "cs.id")
                ->join("calidad_soldador_proyecto as csp", "csp.id", "cj.soldador_proyecto_id")
                ->join("certificaciones_procedimiento as cp", "cp.id", "csp.certificacion_procedimiento_id")
                ->join("calidad_soldadores as cs2", "cs2.id", "cp.soldador_id")
                ->join("calidad_juntas_materiales as cjm1", "cjm1.id", "cj.material_uno_id")
                ->join("calidad_juntas_materiales as cjm2", "cjm2.id", "cj.material_dos_id")
                ->join("coladas_inspeccion as ci1", "ci1.id", "cjm1.colada_inspeccion_id")
                ->join("coladas_inspeccion as ci2", "ci2.id", "cjm2.colada_inspeccion_id")
                ->where("ss.id", $inspeccion->servicios_sistema_id) // sistema
                // ->where("ss.servicio_id", $inspeccion->procedimiento_id) // servicio
                ->select(
                    "cj.nombre",
                    "cj.diametro",
                    "cj.espesor",
                    "cs2.clave",
                    "ss.plano",
                    "cj.hoja",
                    "cj.fecha_inspeccion",
                    "cj.id",
                    "cjm1.nombre_corto as nombre_corto1",
                    "ci1.colada as colada1",
                    "cjm2.nombre_corto as nombre_corto2",
                    "ci2.colada as colada2"
                )
                ->get();
            // obtener nomeclaturas
            $nomens = DB::table("calidad_nomens_visual as cnv")
                ->join("calidad_nomenclaturas as cn", "cn.id", "cnv.nomen_id")
                ->select("clave", "nombre")
                ->where("inspec_visual_id", $id_inspeccion)->get()->toArray();
            $array_nomens = array_chunk($nomens, 3);
            /** ERROR */
            // Invalid characters passed for attempted conversion, these have been ignored
            error_reporting(E_ALL ^ E_DEPRECATED);
            $pdf = PDF::loadView('pdf.inspeccionvisual', compact("inspeccion", "juntas", "array_nomens"));

            $pdf->setPaper("letter", "portrait");
            return $pdf->stream($inspeccion->folio);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e, "generar reporte de trazabilidad");
            return view("errors.500");
        }
    }

    /**
     * Registra una nueva nomenclatura para la inspeccion ingresada
     */
    public function RegistrarNomen(Request $request)
    {
        try
        {
            $nomen = new NomenclaturaVisual($request->all());
            $nomen->save();
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "registrar la nomenclatura");
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
