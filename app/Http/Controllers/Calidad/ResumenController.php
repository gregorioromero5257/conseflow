<?php

namespace App\Http\Controllers\Calidad;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Status;
use Exception;
use Illuminate\Support\Facades\DB;

class ResumenController extends Controller
{
    /**
     * Obtiene las RIMES del proyecto seleccionado
     */
    public function ObtenerRimes($proyecto_id)
    {
        try
        {
            $rimes = DB::table("calidad_proyectos as cp")
                ->join("proyectos as p", "p.id", "cp.proyecto_id")
                ->join("rime_calidad as rc", "rc.proyecto_id", "p.id")
                ->where("p.id", $proyecto_id) // Proyecto.id
                ->select(
                    "rc.*"
                )
                ->orderBy("rc.folio")
                ->get();
            $array_partidas = [];
            foreach ($rimes as $rime)
            {
                $partidas = DB::table("inspecciones_calidad as ic")
                    ->join("articulos as a", "a.id", "ic.articulo_id")
                    ->where("ic.rime_calidad_id", $rime->id)
                    ->select(
                        "ic.id",
                        "a.descripcion",
                        "ic.cantidad",
                        "ic.no_certificado"
                    )
                    ->orderBy("a.descripcion")
                    ->get();
                // Añadir las partidas a la rime actual
                $aux_rime = (array) $rime;
                $aux_rime["partidas"] = $partidas;
                array_push($array_partidas, $aux_rime);
            }
            return Status::Success("rimes", $array_partidas);
        }
        catch (Exception $e)
        {;
            return Status::Error($e, "obtener las rimes");
        }
    }

    /**
     * Obtiene las RIMES del proyecto seleccionado
     */
    public function ObtenerRimesPC($proyecto_id)
    {
        try
        {
            $rimes = DB::table("proyectos as p")
                ->join("rime_calidad_cliente as rcc", "rcc.proyecto_id", "p.id")
                ->where("p.id", $proyecto_id) // Proyecto.id
                ->select(
                    "rcc.*"
                )->get();
            $array_partidas = [];
            foreach ($rimes as $rime)
            {
                $partidas = DB::table("inspecciones_calidad_cliente as icc")
                    ->where("icc.rime_id", $rime->id)
                    ->select(
                        "*"
                    )->get();
                // Añadir las partidas a la rime actual
                $aux_rime = (array) $rime;
                $aux_rime["partidas"] = $partidas;
                array_push($array_partidas, $aux_rime);
            }
            return Status::Success("rimespc", $array_partidas);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las rimes");
        }
    }

    /**
     * Obtiene todas las juntas del proyecto seleccionado
     */
    public function ObtenerJuntas($proyecto_id)
    {
        try
        {
            // Obtener sistemas
            $sistemas =   DB::table("proyectos as p")
                ->join("calidad_proyectos as cp", "cp.proyecto_id", "p.id")
                ->join("calidad_sistemas as cs", "cs.proyecto_id", "cp.id")
                ->join("servicios_sistema as ss", "ss.sistema_id", "cs.id")
                ->where("p.id", $proyecto_id)
                ->select(
                    "ss.id as ss_id",
                    "cs.nombre",
                    "ss.plano"
                )->get();

            $array_juntas = [];
            foreach ($sistemas as $sistema)
            {
                $juntas = DB::table("calidad_spools as sp")
                    ->join("calidad_juntas as cj", "cj.spool_id", "sp.id")
                    ->join("calidad_juntas_materiales as cjm1", "cjm1.id", "cj.material_uno_id")
                    ->join("calidad_juntas_materiales as cjm2", "cjm2.id", "cj.material_dos_id")
                    ->join("calidad_soldador_proyecto as csp", "csp.id", "cj.soldador_proyecto_id")
                    ->join("certificaciones_procedimiento as cp", "csp.certificacion_procedimiento_id", "cp.id")
                    ->join("coladas_inspeccion as ci1", "ci1.id", "cjm1.colada_inspeccion_id")
                    ->join("coladas_inspeccion as ci2", "ci2.id", "cjm2.colada_inspeccion_id")
                    ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                    ->join("calidad_soldadores as cs", "cs.id", "cp.soldador_id")
                    ->join("empleados as e", "e.id", "cs.empleado_id")
                    ->where("sp.servicio_sistema_id", $sistema->ss_id)
                    ->select(
                        "sp.no as spool_no",
                        "cj.id",
                        "cj.nombre",
                        "cj.diametro",
                        "cj.espesor",
                        "cj.estado",
                        "cj.hoja",
                        "cj.fecha_habilitado",
                        "cj.fecha_soldado",
                        "cj.fecha_inspeccion",
                        "cjm1.id as yolo1",
                        "cjm1.nombre_corto as nombre_material1",
                        "cjm2.id as cci2_id",
                        "cjm2.nombre_corto as nombre_material2",
                        "csp.id as csp_id",
                        "cps.clave as procedimiento",
                        "cps.id as procedimiento_id",
                        "cs.clave as clave_soldador",
                        "ci1.colada as colada_uno",
                        "ci2.colada as colada_dos",
                        DB::raw("concat(cs.clave,' - ',e.nombre, ' ',e.ap_paterno,' ',e.ap_materno) as clave_nombre"),
                        DB::raw("CONCAT_WS(' -  ',cps.clave,cps.descripcion ) as nombre_aux")
                    )->get();
                array_push($array_juntas, ["sistema" => $sistema, "juntas" => $juntas]);
            }

            return Status::Success("juntas", $array_juntas);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las rimes");
        }
    }

    /**
     * Obtiene todas los soldadores del proyecto sele
     */
    public function ObtenerSoladadores($proyecto_id)
    {
        try
        {
            $soldadores = DB::table("calidad_proyectos as cp")
                ->join("proyectos as p", "p.id", "cp.proyecto_id")
                ->join("calidad_soldador_proyecto as csp", "csp.proyecto_id", "csp.proyecto_id")
                ->join("certificaciones_procedimiento as cp2", "cp2.id", "csp.certificacion_procedimiento_id")
                ->join("calidad_soldadores as cs", "cs.id", "cp2.soldador_id")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp2.procedimiento_id")
                ->join("calidad_calibracion_maquinas as ccm", "ccm.id", "csp.maquina_id")
                ->join("calidad_maquinas_soldar as cms", "cms.id", "ccm.maquina_id")
                ->where("cp.proyecto_id", $proyecto_id)
                ->select(
                    "cs.clave",
                    DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as sold_nombre"),
                    "ccm.fecha as maq_fecha_cert",
                    "ccm.documento as maq_cert",
                    "cms.marca",
                    "cms.no_control",
                    "cps.clave as proced_clave"
                )->get();

            return Status::Success("soldadores", $soldadores);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las rimes");
        }
    }

    /**
     * Obtiene los datos para las graficas del resumen general 
     */
    public function AvenceSoldadura($proyecto_id)
    {
        try
        {
            $total = $this->JuntasEstado($proyecto_id, "!=", -1); // Obtener todas
            $nuevas = $this->JuntasEstado($proyecto_id, "=", 0); // Obtener nuevas
            $habilitadas = $this->JuntasEstado($proyecto_id, "=", 1); // Obtener habilitadas
            $soldadas = $this->JuntasEstado($proyecto_id, "=", 2); // Obtener soldadas
            $inspeccionadas = $this->JuntasEstado($proyecto_id, "=", 3); // Obtener Inspeccionadas
            return Status::Success(
                "totales",
                [
                    "total" => $total,
                    "nuevas" => $nuevas,
                    "habilitadas" => $habilitadas,
                    "soldadas" => $soldadas,
                    "inspeccionadas" => $inspeccionadas
                ]
            );
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener el avance de soldadaura");
        }
    }

    /**
     * Obtiene todas las juntas del estado y proyecto ingresado
     */
    public function JuntasPorEstado($data)
    {
        try
        {
            $dts = explode("&", $data);
            if (count($dts) < 2)
                return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);

            $juntas = $this->JuntasEstado($dts[0], "=", $dts[1], false); //Proyecto_id, Estado
            return Status::Success("juntas", $juntas);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las juntas");
        }
    }
    /**
     * Obtiene las juntas del estado ingresado
     * @param int proyecto_id Id del proyecto
     * @param string condicion '=','!='
     * @param int estado Estado de la junta
     * @param bool total true para obtener el número total de juntas. false para obtener las juntas
     */
    private function JuntasEstado($proyecto_id, $condicion = "=", $estado = 0, $total = true)
    {
        $juntas = DB::table("proyectos as p")
            ->join("calidad_proyectos as cp", "cp.proyecto_id", "p.id")
            ->join("calidad_sistemas as cs", "cs.proyecto_id", "cp.id")
            ->join("servicios_sistema as ss", "ss.sistema_id", "cs.id")
            ->join("calidad_spools as cs2", "cs2.servicio_sistema_id", "ss.id")
            ->join("calidad_juntas as cj", "cj.spool_id", "cs2.id")
            ->where("p.id", $proyecto_id)
            ->where("cj.estado", $condicion, $estado);
        $n = 0;
        if ($total)
        {
            return $juntas->count(); // Total de juntas
        }
        else
        {
            return $juntas->select("cs2.no as spool_no", "cj.*")->get(); // Datos de las juntas
        }
    }
}
