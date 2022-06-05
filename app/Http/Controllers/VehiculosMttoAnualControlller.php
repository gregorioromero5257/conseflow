<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Helpers\Utilidades;
use App\Unidades;
use App\VehiculosMttoAnual;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;

class VehiculosMttoAnualControlller extends Controller
{

    /**
     * Otiene los años
     */
    public function ObtenerAnios($empresa)
    {
        try
        {
            $anios = DB::table("vehiculos_mtto_anual")
                ->where("empresa", $empresa)->select("anio")->groupBy("anio")->get();
            return Status::Success("anios", $anios);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los reportes");
        }
    }

    /**
     * Obtiene las unidades de la empresa ingresada
     */
    public function ObtenerUnidades($empresa_id)
    {
        try
        {
            $vehiculos = Unidades::where("empresa", $empresa_id)
                ->select(
                    "id",
                    DB::raw("CONCAT_WS(' - ',unidad,modelo,placas) as nombre"),
                    "placas"
                )->get();
            return Status::Success("unidades", $vehiculos);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los vehiculos");
        }
    }

    /**
     * Obtener Mantenimientos 
     */
    public function ObtenerMtto($data)
    {
        try
        {
            $dts = explode("&", $data);
            $mantenimientos = $this->ObtenerMttoAux($dts[0], $dts[1]);
            return Status::Success("mantenimientos", $mantenimientos);
        }
        catch (Exception $e)
        {
            return Status::Error($e, "obtener los mantenimientos");
        }
    }
    public function ObtenerMttoAux($empresa_id, $anio)
    {
        try
        {
            $mttos = DB::table("vehiculos_mtto_anual as vma")
                ->join("unidades as u", "u.id", "vma.unidad_id")
                ->where("vma.empresa", $empresa_id)
                ->where("vma.anio", $anio)
                ->select(
                    "vma.*",
                    "u.placas",
                    DB::raw("CONCAT_WS(' ',unidad,modelo) as vehiculo"),
                    DB::raw("CONCAT_WS(' - ',unidad,modelo,placas) as nombre")
                )->get();
            return $mttos;
        }
        catch (Exception $e)
        {
            dd($e);
        }
    }

    /**
     * Registra un mantenimiento
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $mtto = new VehiculosMttoAnual($request->all());
                Utilidades::auditar($mtto, 0);
                $mtto->save();
            }
            else
            {
                $mtto = VehiculosMttoAnual::find($request->id);
                $mtto->fill($request->all());
                Utilidades::auditar($mtto, $mtto->id);
                $mtto->update();
            }
            return Status::Success();
        }
        catch (Exception $e)
        {
            dd($e);
            return Status::Error($e, "registrar el mantenimiento");
        }
    }

    /**
     * Descarga el formato del mantenimiento
     */
    public function Descargar($data)
    {
        try
        {
            $dts = explode("&", $data);
            if (count($dts) != 2)
            {
                return "<h1>Datos incompletos</h1>";
            }

            $mttos = $this->ObtenerMttoAux($dts[0], $dts[1]);
            $empresa = $dts[0];
            $anio = $dts[1];

            $pdf = PDF::loadView('pdf.vehiculomttoanual', compact("empresa", "mttos", "anio"));
            $pdf->getDomPDF()->set_option("enable_php", true);
            $pdf->setPaper('letter', 'landscape');
            $nombre = "Programación de Servicios Vehículares" . ($dts[0] == 1 ? "CONSERFLOW" : "CSCT") . $dts[1];
            error_reporting(E_ALL ^ E_DEPRECATED);
            return $pdf->stream($nombre);
        }
        catch (Exception $e)
        {
            dd($e);
            return view("errors.500");
        }
    }
}
