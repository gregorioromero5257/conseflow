<?php

namespace App\Http\Controllers\Calidad;

use App\Clientes;
use App\Empleado;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\InspeccionCalidadCliente;
use App\Proyecto;
use App\RimeCalidadCliente;
use Barryvdh\DomPDF\Facade as PDF;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InspeccionClienteController  extends Controller
{
    /**
     * Obtiene todas las inspecciones
     */
    public function Index($id_proyecto)
    {
        $rimes = DB::table("rime_calidad_cliente as rcc")
            ->join("proyectos as p", "p.id", "rcc.proyecto_id")
            ->leftJoin("clientes as c", "c.id", "p.cliente_id")
            ->join("empleados as e1", "rcc.reviso_id", "e1.id")
            ->join("empleados as e2", "rcc.enterado_id", "e2.id")
            ->join("empleados as e3", "rcc.aprobo_id", "e3.id")
            ->where("rcc.proyecto_id", $id_proyecto)
            ->select(
                "rcc.id",
                "rcc.no_proyecto",
                "p.id as proyecto_id",
                "p.nombre_corto as proyecto",
                "c.nombre as cliente",
                "rcc.fecha",
                "rcc.folio",
                "e1.id as reviso_id",
                "e2.id as enterado_id",
                "e3.id as aprobo_id",
                DB::raw("CONCAT(e1.nombre,' ',e1.ap_paterno,' ',e1.ap_materno) AS reviso"),
                DB::raw("CONCAT(e2.nombre,' ',e2.ap_paterno,' ',e2.ap_materno) AS enterado"),
                DB::raw("CONCAT(e3.nombre,' ',e3.ap_paterno,' ',e3.ap_materno) AS aprobo")
            )
            ->orderBy("folio")
            ->get();

        $rimes1 = RimeCalidadCliente::where("proyecto_id", $id_proyecto)
            ->orderBy("folio")->get();
        return $this->Success("inspecciones", $rimes);
    }

    /**
     * Obtiene la inspecciones de la rime
     */
    public function Inspecciones($id)
    {
        try
        {
            $inspeccion = InspeccionCalidadCliente::where("rime_id", $id)->get();
            return $this->Success("inspecciones", $inspeccion);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al obtener las inspecciones");
        }
    }

    /**
     * Muestra el cliente del proyecto ingresado
     */
    public function Cliente($proyecto_id)
    {
        try
        {
            $proyecto = Proyecto::find($proyecto_id);
            $cliente = Clientes::find($proyecto->cliente_id);
            $nombre = $cliente == null ? "N/D" : $cliente->nombre;
            return $this->Success("cliente", $nombre);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Registra o actualiza una inspeeción 
     */
    public function Guardar(Request $request)
    {
        try
        {
            $inspeccion = null;
            if ($request->id == null)
                $inspeccion = new InspeccionCalidadCliente();
            else
                $inspeccion = InspeccionCalidadCliente::find($request->id);
            $inspeccion->fill($request->all());
            if ($request->tipo_equipo_id == 1)
            {
                $inspeccion->tipo_equipo = "X";
                $inspeccion->tipo_inssto = "N/A";
            }
            else
            {
                $inspeccion->tipo_equipo = "N/A";
                $inspeccion->tipo_inssto = "X";
            }
            if ($request->id == null)
                $inspeccion->save();
            else
                $inspeccion->update();
            // Guardar docuemento
            if ($request->hasFile('certificado_pdf'))
            {
                $doc = $request->file("certificado_pdf");
                $nombre = $doc->getClientOriginalName();
                $hoy_aux = date("y_m_d_h_i_s");
                $aux_nombre = $hoy_aux . "_" . $nombre;
                $ruta_documento = "Archivos/Rime_Cliente/" . $aux_nombre;
                Storage::disk('local')->put($ruta_documento, fopen($doc, 'r+'));
                $inspeccion->certificado = $aux_nombre;
                $inspeccion->save();
            }
            return $this->Success("rime", $inspeccion->rime_id);
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al guardar la inspección");
        }
    }

    /**
     * Crear nueva Rime (Encabezado)
     */
    public function CrearRime(Request $request)
    {
        try
        {
            $rime = new RimeCalidadCliente($request->all());
            $n = DB::table("rime_calidad_cliente")
                ->where("proyecto_id", $request->proyecto_id)
                ->count();
            $proyecto = Proyecto::find($request->proyecto_id);
            // dd($proyecto);
            if ($proyecto == null) return $this->Error(null, "Error al obtener proyecto");
            $folio = "RIEPC-" . $proyecto->nombre_corto . "-" . str_pad($n + 1, 3, "0", STR_PAD_LEFT);
            $rime->folio = $folio;
            $rime->save();
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al crear la inspección");
        }
    }

    /**
     * Descarga el certificado de la inpsección
     */
    public function Certificado($id)
    {
        try
        {
            // obtener rime
            $inspeccion = InspeccionCalidadCliente::find($id);
            $path = storage_path("app/Archivos/Rime_Cliente/" . $inspeccion->certificado);
            if (file_exists($path))
            {
                return response()->download($path, $inspeccion->certificado, [
                    'Content-Type' => "application/pdf",
                    'Content-Disposition' => 'inline; filename="' . $inspeccion->certificado . '"'
                ]);
            }
            return "<h3>Archivo no encontrado</h3>";
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return "<h1>Error al obtener certificado</h1>";
        }
    }

    /**
     * Obtiene los proyectos
     */
    public function Proyectos()
    {
        try
        {
            $proyectos = Proyecto::where("condicion", 1)
                ->leftJoin("clientes as c", "cliente_id", "c.id")
                ->orderBy("nombre_corto")
                ->select("proyectos.id", "nombre_corto as nombre", "c.nombre as cliente")
                ->get();
            return $this->Success("proyectos", $proyectos);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene los empleados activos
     */
    public function Empleados()
    {
        $empleados = Empleado::where("condicion", 1)
            ->select("id", DB::raw("concat_ws(' ',nombre,ap_paterno, ap_materno) as nombre"))
            ->orderBy("nombre")
            ->get();
        return $this->Success("empleados", $empleados);
    }

    /**
     * Descargar rime
     */
    public function DescargarRime($id)
    {
        try
        {
            // Debe
            $rime = DB::table("rime_calidad_cliente as rcc")
                ->join("proyectos as p", "p.id", "rcc.proyecto_id")
                ->leftJoin("clientes as c", "c.id", "p.cliente_id")
                ->join("empleados as e1", "rcc.reviso_id", "e1.id")
                ->join("empleados as e2", "rcc.enterado_id", "e2.id")
                ->join("empleados as e3", "rcc.aprobo_id", "e3.id")
                ->where("rcc.id", $id)
                ->select(
                    "rcc.id",
                    "rcc.no_proyecto",
                    "p.nombre_corto as proyecto",
                    "c.nombre as cliente",
                    "rcc.fecha",
                    "rcc.folio",
                    DB::raw("CONCAT(e1.nombre,' ',e1.ap_paterno,' ',e1.ap_materno) AS reviso"),
                    DB::raw("CONCAT(e2.nombre,' ',e2.ap_paterno,' ',e2.ap_materno) AS enterado"),
                    DB::raw("CONCAT(e3.nombre,' ',e3.ap_paterno,' ',e3.ap_materno) AS aprobo")
                )->first();

            $inspecciones = DB::table("inspecciones_calidad_cliente as icc")
                ->where("icc.rime_id", $id)
                ->select(
                    "*"
                )->get();

            $dia = substr($rime->fecha, 8, 2);
            $mes = substr($rime->fecha, 5, 2);
            $anio = substr($rime->fecha, 0, 4);

            $fecha = $dia . '/' . $mes . '/' . $anio;
            $pdf = PDF::loadView("pdf.rimecliente", compact("rime", "fecha", "inspecciones"));
            $pdf->setPaper('ledger', 'portrait');
            return $pdf->stream($rime->folio . ".pdf");
        }
        catch (Exception $e)
        {
            $this->Error($e);
            return view("errors.500");
        }
    }

    public function Success($nombre = "", $data = [])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    public function Error($e, $mensaje = "Error al obtener los datos")
    {
        if ($e != null)
            Utilidades::errors($e);
        return response()->json(["status" => false, "mensaje" => $mensaje]);
    }
}
