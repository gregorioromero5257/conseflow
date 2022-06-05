<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\ProeveedorUnidad;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Status;
use App\InspeccionCalidad;
use App\Proyecto;
use App\CalidadModels\Recepcion;
use App\CalidadModels\RecepcionPartida;
use App\RimeCalidad;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;

class RecepcionController extends Controller
{

    /**
     * Obtiene las recepciones del proyecto seleccionado
     */
    public function Obtener($p_id)
    {
        try
        {
            $recepciones = $this->ObtenerAux($p_id); // por proyecto

            return Status::Success("recepciones", $recepciones);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtner las RIMES");
        }
    }

    /**
     * Obtener las partidas de la recepción ingresada
     */
    public function ObtenerPartidas($id)
    {
        try
        {
            $partidas = $this->ObtenerPartidasAux($id);
            return Status::Success("partidas", $partidas);
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }

    public function ObtenerPartidasAux($id)
    {
        $partidas = DB::table("calidad_partida_recepcion as cpr")
            ->join("articulos as a", "a.id", "cpr.articulo_id")
            ->where("cpr.recepcion_id", $id)
            ->select("a.descripcion", "a.marca", "cpr.cantidad")->get();
        return $partidas;
    }



    public function ObtenerAux($id, $tipo = 0)
    {
        try
        {
            $recepciones = DB::table("calidad_recepcion as cp")->join("ordenes_compras as oc", "oc.id", "oc_id")
                ->join("empleados as e", "e.id", "empleado_recibe")
                ->join("empleados as e1", "e1.id", "cp.empleado_aprobo")
                ->join("empleados as e2", "e2.id", "cp.empleado_reviso")
                ->join("proveedores as p", "p.id", "oc.proveedore_id")
                ->join("proyectos as pr", "pr.id", "cp.proyecto_id")
                ->select(
                    DB::raw("CONCAT_WS(' ',e1.ap_paterno,e1.ap_materno,e1.nombre) as empleado_aprobo_nombre"),
                    DB::raw("CONCAT_WS(' ',e2.ap_paterno,e2.ap_materno,e2.nombre) as empleado_reviso_nombre"),
                    "p.id as p_id",
                    "pr.nombre_corto",
                    "p.nombre as proveedor",
                    "cp.*",
                    "oc.folio as oc",
                    DB::raw("CONCAT_WS(' ',e.ap_paterno,e.ap_materno,e.nombre) as empleado_recibe")
                );
            if ($tipo == 0) // por proyecto
                $recepciones = $recepciones->where("cp.proyecto_id", $id)->get();
            else $recepciones = $recepciones->where("cp.id", $id)->first(); // por id
            return $recepciones;
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }

    /**
     * Obtener las ordenes de compra del proyecto seleccionado
     */
    public function ObtenerOc($proyecto_id)
    {
        try
        {
            $ocs = DB::table("entradas as e")
                ->join("ordenes_compras as oc", "oc.id", "e.orden_compra_id")
                ->join("proveedores as p", "p.id", "oc.proveedore_id")
                ->where("oc.proyecto_id", $proyecto_id)
                ->select(
                    "oc.id",
                    "oc.folio",
                    "p.nombre as proveedor"
                )->get();
            return Status::Success("ocs", $ocs);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las ordenes de compra");
        }
    }

    /**
     * Obtiene las entradas de la orden de compra seleccionada
     */
    public function ObtenerArticulos($oc_id)
    {
        try
        {
            $articulos = [];
            return Status::Success("articulos", $articulos);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener los artículos");
        }
    }

    /**
     * Obtiene todos los proyectos 
     */
    public function Proyectos()
    {
        try
        {
            $proyectos = Proyecto::where("condicion", 1)
                ->orderBy("nombre_corto")
                ->select("id", "nombre_corto")->get();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Generar las recepciones de la rime
     */
    public function Generar(Request $request)
    {
        $asd = null;
        $asd1 = null;
        try
        {
            $proyecto_id = $request->proyecto_id;
            $proyecto = Proyecto::where("id", $proyecto_id)->select("nombre_corto")->first();
            DB::beginTransaction();
            $rimes = RimeCalidad::where("rime_calidad.proyecto_id", $proyecto_id)
                ->join("ordenes_compras as oc", "oc.id", "rime_calidad.oc_id")
                ->join("proveedores as p", "p.id", "oc.proveedore_id")
                ->select("rime_calidad.*", "p.id as p_id")->get();

            // Generar todas las recepciones con los datos de la rime
            foreach ($rimes as $i => $rime)
            {
                $h = rand(10, 16);
                $m = rand(0, 56);
                $hora = $h . ":" . ($m < 10 ? "0" : "") . $m;

                // Obtener unidad del proveedore
                $prov_unid = ProeveedorUnidad::where("proveedor_id", $rime->p_id)->first();
                if ($prov_unid == null)
                {
                    $asd1++;
                    // echo $rime->p_id . "<br>";
                    $prov_unid = new ProeveedorUnidad();
                    $prov_unid->unidad = "";
                    $prov_unid->placas = "";
                    $prov_unid->conductor = "";
                }

                // Crear recepcion
                $recepcion = new Recepcion();
                $recepcion->proyecto_id = $proyecto_id;
                $recepcion->folio = "RECEP-" . $proyecto->nombre_corto . "-0" . ($i + 1);
                $recepcion->fecha = $rime->fecha;
                $recepcion->hora = $hora;
                $recepcion->oc_id = $rime->oc_id;
                $recepcion->coincide_solicitado = 1;
                $recepcion->danios_visivles = 0;
                $recepcion->condiciones_aceptables = 1;
                $recepcion->metarial_aceptado = 1;
                $recepcion->motivo = "-"; // Todo ok
                $recepcion->unidad = $prov_unid->unidad;
                $recepcion->placas = $prov_unid->placas;
                $recepcion->no_almacen = "GENERAL";
                $recepcion->observaciones = "-";
                $recepcion->persona_entrega = $prov_unid->conductor;
                $recepcion->empleado_recibe = $rime->empleado_reviso;
                $recepcion->empleado_reviso = $rime->empleado_reviso;
                $recepcion->empleado_aprobo = $rime->empleado_aprobo;
                $recepcion->save();
                // Crear partidas de la recepción
                $partidas_rime = InspeccionCalidad::where("rime_calidad_id", $rime->id)->get();
                foreach ($partidas_rime as $partida)
                {
                    $recep_partida = new RecepcionPartida();
                    $asd = $recep_partida;
                    $recep_partida->recepcion_id = $recepcion->id; // Id de recepcion
                    $recep_partida->cantidad = $partida->cantidad; // Cantidad
                    $recep_partida->articulo_id = $partida->articulo_id; // Id de articulo
                    // DAtos de partida
                    $recep_partida->tiene_oc = 1;
                    $recep_partida->coincide_oc = $partida->ins_mat_factura == "A" ? 1 : 0;
                    $recep_partida->tiene_factura = 1;
                    $recep_partida->tiene_certificado = $partida->ins_mat_cetificado == "A" ? 1 : 0;
                    $recep_partida->coincice_solicitado = $partida->ins_mat_dimesiones == "A" ? 1 : 0;
                    $recep_partida->danios_material = $partida->ins_mat_edo_fisico == "R" ? 1 : 0;
                    $recep_partida->condiciones_aceptables = 1;
                    $recep_partida->aceptado = 1;
                    $recep_partida->save();
                }
            }
            // dd($asd1);
            DB::commit();
            return Status::Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            dd($e);
        }
    }

    /**
     * Obtener la unidad del proveedor ingresado
     */
    public function ObtenerUnidad($p_id)
    {
        try
        {
            $unidad = ProeveedorUnidad::first();

            return $unidad;
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }
    /**
     * Genera el reporte en PDF
     */
    public function Descargar($rid)
    {
        try
        {
            // Obtener recepcion
            $recepcion = $this->ObtenerAux($rid, 1);
            $partidas = $this->ObtenerPartidasAux($recepcion->id);
            $unidad = $this->ObtenerUnidad($recepcion->p_id); // unidad del proveedor
            $fecha_reporte = strtotime($recepcion->fecha);
            $fecha_sgi = strtotime("2020-08-18");
            $tipo = $fecha_reporte >= $fecha_sgi ? 2 : 1;
            $pdf = PDF::loadView('pdf.calidadrecepcionold', compact("recepcion", "partidas", "unidad", "tipo"));
            $pdf->setPaper('letter', 'landscape');
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream();
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }

    /**
     * Carga las unidades de los proveedores
     */
    public function CargarUnidades(Request $request)
    {
        try
        {
            if (!($request->token == "#Yolo123dx")) return "Nel, prro";
            $unidades = (new FastExcel)->import($request->file('unidades')->getRealPath());
            // dd($unidades);
            foreach ($unidades as $unidad)
            {
                if ($unidad["proveedor_id"] == "") continue;
                $nuevo = new ProeveedorUnidad($unidad);
                $nuevo->save();
            }
            echo "ok";
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }
}
