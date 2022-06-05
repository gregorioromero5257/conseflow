<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Support\Facades\DB;

class RHResguardosController extends Controller
{

    /**
     * Obtiene los resguardos del empleado ingresado
     */
    public function ObtenerPorEmpleado($empleado_id)
    {
        try
        {
            /** TI */
            // Equipo TI
            $computo = DB::table("ti_material_resguardo as tmr")
                ->join("ti_computo as ta", "ta.id", "tmr.caiv")
                ->where("tmr.empleado_recibe", $empleado_id)
                ->where("tipo", 1)
                ->where("tmr.estado", 1)
                ->select(
                    "tmr.id as trm_id",
                    "fecha",
                    "tmr.acesorios",
                    "caiv as a_id",
                    "tipo",
                    "tmr.cantidad",
                    DB::raw("CONCAT_ws(' ',no_serie,marca_modelo, cpu,ram,almacenamiento,tarjeta_video,tarjeta_red, observaciones,mac) AS descripcion")
                )->get()->toArray();

            $accesorios = DB::table("ti_material_resguardo as tmr")
                ->join("ti_accesorios as ta", "ta.id", "tmr.caiv")
                ->where("tmr.empleado_recibe", $empleado_id)
                ->where("tipo", 2)
                ->where("tmr.estado", 1)
                ->select(
                    "tmr.id as trm_id",
                    "fecha",
                    "caiv as a_id",
                    "tipo",
                    "tmr.cantidad",
                    DB::raw("CONCAT_ws(' ',descripcion,modelo,marca,no_serie) AS descripcion")
                )->get()->toArray();

            $impresoras = DB::table("ti_material_resguardo as tmr")
                ->join("ti_impresoras as ti", "ti.id", "tmr.caiv")
                ->where("tmr.empleado_recibe", $empleado_id)
                ->where("tipo", 3)
                ->where("tmr.estado", 1)
                ->select(
                    "tmr.id as trm_id",
                    "fecha",
                    "caiv as a_id",
                    "tipo",
                    "tmr.cantidad",
                    DB::raw("CONCAT_ws(' ',descripcion,modelo,marca,no_serie) AS descripcion")
                )->get()->toArray();

            $video = DB::table("ti_material_resguardo as tmr")
                ->join("ti_video as tv", "tv.id", "tmr.caiv")
                ->where("tmr.empleado_recibe", $empleado_id)
                ->where("tipo", 4)
                ->where("tmr.estado", 1)
                ->select(
                    "tmr.id as trm_id",
                    "fecha",
                    "caiv as a_id",
                    "tipo",
                    "tmr.cantidad",
                    DB::raw("CONCAT_ws(' ',descripcion,no_serie) AS descripcion")
                )->get()->toArray();

            /*$red = DB::table("ti_material_resguardo as tmr")
                ->join("ti_red as tr", "tr.id", "tmr.caiv")
                ->where("tmr.empleado_recibe", "=", "150")
                ->where("tipo", "=", "5")
                ->where("tmr.estado", "=", "1")
                ->select(
                    "tmr.id as trm_id",
                    "fecha",
                    "caiv as a_id",
                    "tipo",
                    "CONCAT_ws(' '",
                    "descripcion",
                    "no_serie) AS descripcion",
                )->get();*/

            /** Seguridad */
            $seguridad = DB::table("empleados_vale_epp as eve")
                ->join("partidas_vale_epp as pve", "pve.empleado_vale_id", "eve.id")
                ->join("articulos as a", "a.id", "pve.articulo_id")
                ->where("eve.empleado_id", $empleado_id)
                ->select("eve.id as trm_id", "pve.cantidad", "pve.fecha", "a.descripcion")
                ->get()->toArray();

            /** Almacen */
            $almacen=DB::table("salidasresguardo as s")
            ->join("partidas as p","p.salida_id","s.id")
            ->join("lote_almacen as la","la.id","p.lote_id")
            ->join("articulos as a","a.id","la.articulo_id")
            ->join("empleados as e","e.id","s.empleado_solicita_id")
            ->where("p.tiposalida_id",3)
            ->where("e.id",$empleado_id)
            ->select(
                "s.id as tmr_id",
                "p.cantidad",
                "s.fecha",
                "a.descripcion"
            )
            ->get();

            $equipos = array_merge($computo, $accesorios, $video, $impresoras);
            return response()->json(["status" => true, "resguardos" => [$equipos, $seguridad,$almacen]]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json([
                "status" => false,
                "mensjae" => "Erro al obtener los equipos"
            ]);
        }
    }

    /**
     * Obtiene los empleados que tienen un equipo en resguardo
     */
    public function ObtenerEmpleados()
    {
        try
        {
            $emp_ti = DB::table("ti_material_resguardo as tmr")
                ->join("empleados as e", "e.id", "tmr.empleado_recibe")
                ->select("e.id", DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre"))
                ->distinct()
                ->get()->toArray();

            $emp_segu = DB::table("empleados_vale_epp as epp")
                ->join("empleados as e", "e.id", "epp.empleado_id")
                ->select("e.id", DB::raw("concat_ws(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre"))
                ->distinct()
                ->get()->toArray();
            $empleados = array_merge($emp_ti, $emp_segu);
            return response()->json(["status" => true, "empleados" => $empleados]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => "Error al obtener los empleados"]);
        }
    }
}
