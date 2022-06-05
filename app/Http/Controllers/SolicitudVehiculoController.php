<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Status;
use App\Http\Helpers\Utilidades;
use App\ResponsableSoliVehiculos;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\SolicitudVehiculo;
use App\Unidades;
use Illuminate\Support\Facades\DB;

class SolicitudVehiculoController extends Controller
{
    /**
     * Obtiene todos las solicitudes de vehiculos
     */
    public function Obtener($empresa_id)
    {
        try
        {
            $solicitudes = $this->ObtenerAux(0, $empresa_id);
            return Status::Success("solicitudes", $solicitudes);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e . "obtener las solicitudes");
        }
    }

    /**
     * Aux para obtener solicitudes
     * @param $id int Id de la solicitud
     * @param $tipo int Tipo de obtención: 1. Todos 2. Uno
     */
    public function ObtenerAux($id = 0, $empresa_id)
    {
        $solicitudes = DB::table("vehiculos_solicitud as vs")
            ->join("empleados as e1", "e1.id", "vs.autoriza_id")
            ->join("empleados as e2", "e2.id", "vs.solicita_id")
            ->join("catalogo_tipo_vehiculo as ctv", "ctv.id", "vs.tipo_unidad_id")
            ->leftJoin("proyectos as p", "p.id", "vs.proyecto_id")
            ->leftJoin("unidades as u", "u.id", "vs.unidad_id")
            ->select(
                "u.unidad",
                "u.placas",
                "u.modelo",
                DB::raw("CONCAT_WS(' - ',unidad,modelo,placas) as nombre_unidad"),
                "vs.*",
                "vs.empresa",
                "p.nombre_corto as proyecto",
                "e1.nombre",
                "e2.nombre",
                "ctv.clase_tipo",
                DB::raw("CONCAT_WS(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as autoriza"),
                DB::raw("CONCAT_WS(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as solicita")
            );
        if ($id == 0) // Obtener todos de la empresa
            $solicitudes = $solicitudes->where("vs.empresa", $empresa_id)->get();
        else // Obtener el del id ingresado
            $solicitudes = $solicitudes->where("vs.id", $id)->first();
        return $solicitudes;
    }

    /**
     * Obtiene los responsables de la solicitud
     */
    public function ObtenerResponsables($s_id)
    {
        try
        {
            $responsables = $this->ObtenerResponsablesAux($s_id);
            return Status::Success("responsables", $responsables);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener responsables");
        }
    }
    public function ObtenerResponsablesAux($s_id)
    {
        $responsables = DB::table("vehiculos_solicitud_responsables as vsr")
            ->join("empleados as e", "e.id", "vsr.empleado_id")
            ->where("vsr.solicitud_id", $s_id)
            ->select(
                "vsr.empleado_id",
                DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as nombre")
            )->get();
        return $responsables;
    }

    /**
     * Registra una nueva solicitud
     */
    public function Guardar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            $soli = null;
            // Guardar soli
            if ($request->id == null)
            {
                $soli = new SolicitudVehiculo($request->all());
                Utilidades::auditar($soli, 0);
                $soli->save();
            }
            else
            {
                $soli = SolicitudVehiculo::find($request->id);
                $soli->fill($request->all());
                Utilidades::auditar($soli, $soli->id);
                $soli->update();
            }
            // Guardar responsable
            $ids = explode(",", $request->responsables);
            foreach ($ids as $responsable)
            {
                $ids = explode(",", $request->responsables);
                foreach ($ids as $responsable)
                {
                    $nuevo = new ResponsableSoliVehiculos();
                    $nuevo->solicitud_id = $soli->id;
                    $nuevo->empleado_id = $responsable;
                    $nuevo->save();
                }
            }
            DB::commit();
            return Status::Success();
        }
        catch (Exception $e)
        {
            DB::rollBack();
            dd($e);
            return Status::Error($e, "registrar la solicitud");
        }
    }

    /**
     * Obtiene las unidades por tipo
     */
    public function UnidadesPorTipo($data)
    {
        try
        {
            $dts = explode("&", $data);
            $unidades = DB::table("unidades as u")
                ->where("u.clase_tipo", $dts[1])
                ->where("empresa", $dts[0])
                ->select(
                    DB::raw("CONCAT_WS(' - ',unidad,modelo,placas) as nombre"),
                    "u.id",
                    "u.unidad",
                    "u.modelo",
                    "u.placas"
                )->get();
            return Status::Success("unidades", $unidades);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las unidades");
        }
    }

    /**
     * Genera el reporte en PDF
     */
    public function GenerarReporte($id)
    {
        try
        {
            $solicitud = $this->ObtenerAux($id, 0);
            $responsables = $this->ObtenerResponsablesAux($id);
            $pdf = PDF::loadView('pdf.solicitudvehiculo', compact("solicitud", "responsables"));
            $pdf->setPaper('letter', 'portrait');
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream($solicitud->folio);
        }
        catch (Exception $e)
        {
            return view("errors.500");
        }
    }
}
