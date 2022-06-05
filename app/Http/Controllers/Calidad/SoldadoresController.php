<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\CertificacionProcedimiento;
use App\CalidadModels\Soldador;
use App\Empleado;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SoldadoresController extends Controller
{

    /**
     * Obtiene todos los procedimientos de sldadura
     */
    public function Obtener()
    {
        try
        {
            $soldadores = DB::table("calidad_soldadores as cs")
                ->join("empleados as e", "e.id", "cs.empleado_id")
                ->select(
                    "cs.id",
                    "e.id as empleado_id",
                    "cs.clave",
                    "e.nombre as nombre_aux",
                    "e.ap_paterno",
                    "e.ap_materno",
                    "cs.estado",
                    DB::raw("concat_ws(' ',nombre,ap_paterno, ap_materno) as nombre"),
                    DB::raw("concat(clave,' - ',nombre,' ',ap_paterno,' ', ap_materno) as nombre_clave")
                )->get();
            return $this->Success("soldadores", $soldadores);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene los empleados
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
     * Guarda un soldador
     */
    public function Guardar(Request $request)
    {
        try
        {
            if ($request->id == null)
            {
                $soldador = new Soldador($request->all());
                $soldador->save();
            }
            else
            {
                $soldador = Soldador::find($request->id);
                $soldador->fill($request->all());
                $soldador->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    public function GuardarProcedimiento(Request $request)
    {
        try
        {
            $certificacion = null;
            if ($request->id == null)
            {
                $certificacion = new CertificacionProcedimiento($request->all());
                $certificacion->save();
            }
            else
            {
                $certificacion = CertificacionProcedimiento::find($request->id);
                $certificacion->fill($request->all());
                $certificacion->update();
            }
            // Documento
            if ($request->hasFile("certificado"))
            {
                $doc = $request->file("certificado");
                $nombre = $doc->getClientOriginalName();
                $hoy_aux = date("y_m_d_h_i_s");
                $aux_nombre = $hoy_aux . "_" . $nombre;
                $ruta_documento = "Certificados_soldadores" . DIRECTORY_SEPARATOR . $aux_nombre;
                Storage::disk('local')->put($ruta_documento, fopen($doc, 'r+'));
                $certificacion->certificado = $aux_nombre;
                $certificacion->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e,"Error al guardar certificación");
        }
    }

    /**
     * Obtiene las certificaciones del soldador ingresado
     */
    public function VerProcedimientos($soldador_id)
    {
        try
        {
            $procedimientos = DB::table("certificaciones_procedimiento as cp")
                ->join("calidad_proced_soldadura as cps", "cps.id", "cp.procedimiento_id")
                ->where("cp.soldador_id", "=", $soldador_id)
                ->select(
                    "cp.*",
                    DB::raw("CONCAT_WS(' - ',cps.clave,cps.descripcion) as nombre_aux")
                )->get();

            return $this->Success("procedimientos", $procedimientos);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Obtiene el procedimiento indicado
     */
    public function DetallesProcedimiento($proced_id)
    {
        try
        {
            $certificacion = CertificacionProcedimiento::find($proced_id);
            return $this->Success("certficicacion", $certificacion);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
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
