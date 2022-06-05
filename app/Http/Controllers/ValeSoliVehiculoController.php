<?php

namespace App\Http\Controllers;

use App\Http\Helpers\Utilidades;
use App\PolizaUnidades;
use App\ValeSoliVehiculo;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class ValeSoliVehiculoController extends Controller
{

    /**
     * Obtiene los vales registrados de la empresa ingresada
     */
    public function Obtener($empresa_id)
    {
        try
        {
            $vales = $this->ObtenerAux($empresa_id);
            return Status::Success("vales", $vales);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener los vales de resguardo");
        }
    }
    public function ObtenerAux($id, $tipo = 0)
    {
        try
        {
            $vales = DB::table("vehiculos_vale_resguardo as vvr")
                ->join("vehiculos_solicitud as vs", "vs.id", "vvr.solicitud_id")
                ->join("unidades as u", "u.id", "vs.unidad_id")
                ->join("poliza_unidades as pu", "pu.id", "vvr.poliza_id")
                ->join("empleados as e", "e.id", "vvr.entega_id")
                ->join("empleados as e1", "e1.id", "vs.solicita_id")
                ->select(
                    "vvr.id",
                    "vvr.empresa",
                    "vvr.fecha",
                    "vvr.id as ee_id",
                    "vs.id as vs_id",
                    "vs.fecha_solicitud",
                    "vs.folio as vs_folio",
                    "vs.tiempo as periodo",
                    "u.id as u_id",
                    "u.unidad as u_nombre",
                    "u.placas",
                    "u.marca",
                    "u.modelo",
                    "u.color",
                    "u.numero_serie",
                    "u.no_motor",
                    "u.capacidad",
                    "u.cilindros",
                    "u.numero_tarjeta_circulacion as u_tarjeta",
                    "pu.id as p_id",
                    "pu.proveedor",
                    "pu.numero_poliza as poliza_seguro",
                    "pu.fecha_inicio",
                    "pu.fecha_termino",
                    DB::raw("CONCAT_WS(' ',u.unidad,u.modelo,u.placas) as unidad"),
                    DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as entrega"),
                    DB::raw("CONCAT_WS(' ',e1.nombre,e1.ap_paterno,e1.ap_materno) as responsable")
                );
            if ($tipo == 0)
                $vales = $vales->where("vvr.empresa", $id)->get();
            else
                $vales = $vales->where("vvr.id", $id)->first();
            return $vales;
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }

    /**
     * Obtiene las solicitudes de vehiculos de la empresa ingresada
     */
    public function ObtenerSolicitudes($empresa_id)
    {
        try
        {
            $solis = $this->ObtenerSolicitudesAux($empresa_id);
            return Status::Success("solicitudes", $solis);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener las solicitudes");
        }
    }

    public function ObtenerSolicitudesAux($empresa_id)
    {
        try
        {
            $solis = DB::table("vehiculos_solicitud as vs")
                ->join("unidades as u", "vs.unidad_id", "u.id")
                ->join("empleados as e", "e.id", "vs.solicita_id")
                ->where("vs.empresa", $empresa_id)
                ->select(
                    "vs.id as vs_id",
                    "vs.fecha_solicitud as vs_fecha_solicitud",
                    "vs.folio as s_folio",
                    "vs.tiempo as periodo",
                    "vs.solicita_id as vs_solicita_id",
                    "u.id as unidad_id",
                    "u.unidad",
                    "u.placas",
                    "u.marca",
                    "u.modelo",
                    "u.color",
                    "u.numero_serie as no_serie",
                    "u.no_motor",
                    "u.capacidad as capacidad_carga",
                    "u.cilindros",
                    "u.numero_tarjeta_circulacion as tarjeta",
                    DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as empledo_solicita"),
                    DB::raw("CONCAT_WS(' - ',vs.folio,placas) as unidad_solicitud")
                )->get();

            return $solis;
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las solicitudes");
        }
    }

    /**
     * Obtiene las polizas de la unidad ingresada
     */
    public function ObtenerPolizas($id)
    {
        try
        {
            $polizas = PolizaUnidades::where("unidad_id", $id)
                ->select(
                    "id",
                    "fecha_inicio",
                    "fecha_termino",
                    "proveedor",
                    "numero_poliza",
                    DB::raw("CONCAT_WS(' ',proveedor,numero_poliza,fecha_inicio,fecha_termino) as nombre")
                )->get();
            return Status::Success("polizas", $polizas);
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "obtener las polizas");
        }
    }

    /**
     * Registra un vale de resguardo de la unidad ingresada
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {

                $vale = new ValeSoliVehiculo($request->all());
                Utilidades::auditar($vale, 0);
                $vale->save();
            }
            else
            {
                $vale = ValeSoliVehiculo::find($request->id);
                $vale->fill($request->all());
                Utilidades::auditar($vale, $vale->id);
                $vale->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "guardar el vale de resguardo");
        }
    }

    /**
     * Generar reporte
     */
    public function Descargar($id)
    {
        try
        {
            $meses = [
                'Enero', 'Febrero', 'Marzo', 'Abril',
                'Mayo', 'Junio', 'Julio', 'Agosto',
                'Septiembre', 'Octubre', 'Noviembre', 'Diciembre',
            ];
            $vale = $this->ObtenerAux($id, 1);
            
            $mes_i = explode("-", $vale->fecha_solicitud)[1];
            $dia = explode("-", $vale->fecha_solicitud)[2];
            $anio = explode("-", $vale->fecha_solicitud)[0];
            $mes = $meses[$mes_i - 1];
            $fecha = "$anio / $mes/ $dia";
            $pdf = PDF::loadView('pdf.vehiculosvaleresguardo', compact("vale", "fecha"));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('letter', 'landscape');
            $nombre = "Programación de Servicios Vehículares";
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream($nombre);
        }
        catch (Exception $e)
        {
            dd($e);
            Utilidades::errors($e);
            return view("errors.500");
        }
    }
}
