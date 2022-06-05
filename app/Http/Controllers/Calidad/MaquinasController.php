<?php

namespace App\Http\Controllers\Calidad;

use App\CalidadModels\CalibracionMaquina;
use App\CalidadModels\MaquinaSoldar;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MaquinasController  extends Controller
{

    /**
     * Estado Máquina
     * 1. Disponible (Activo)
     * 2. Asignado
     */

    /**
     * Obtiene las máquinas de soldar registradas
     */
    public function Obtener()
    {
        try
        {
            $maquinas = DB::table("calidad_maquinas_soldar")->orderBy("no_control")->get();
            $list_maquinas = [];
            foreach ($maquinas as $maquina)
            {
                $calibracion = CalibracionMaquina::where("maquina_id", $maquina->id)
                    ->orderBy("id", "desc")->first();
                $maquina = (array)$maquina;
                if ($calibracion == null)
                {
                    $maquina["fecha_calibracion"] = "N/D";
                    $maquina["no_certificado"] = "-";
                }
                else
                {
                    $maquina["fecha_calibracion"] = $calibracion->fecha;
                    $maquina["no_certificado"] = $calibracion->no_certificado;
                }
                $maquina["maquina_id"] = $maquina["id"];
                array_push($list_maquinas, (object)$maquina);
            }
            return $this->Success("maquinas", $list_maquinas);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Guarda o actualiza una maquina de soldar
     */
    public function Guardar(Request $request)
    {
        try
        {
            $maquina = null;
            if ($request->id == null) // Nuevo
            {
                // No. control no se puede repetir
                $existe_nc = MaquinaSoldar::where("no_control", $request->no_control)->first();
                if ($existe_nc != null) return $this->Error(null, "No. de control ya existente");
                $maquina = new  MaquinaSoldar($request->all());
                $maquina->save();
            }
            else // Actualizar
            {
                $maquina = MaquinaSoldar::find($request->id);
                $maquina->fill($request->all());
                $maquina->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al guardar la máquina");
        }
    }

    /**
     * Registra una nueva calibración
     */
    public function GuardarCalibracion(Request $request)
    {
        try
        {
            // Datos
            $calibracion = null;
            if ($request->id == "null" | $request->id == null)
            {
                $calibracion = new CalibracionMaquina($request->all());
                $calibracion->save();
            }
            else
            {
                $calibracion = CalibracionMaquina::find($request->id);
                $calibracion->fill($request->all());
                $calibracion->update();
            }

            // Documento
            if ($request->hasFile("certificado"))
            {
                $doc = $request->file("certificado");
                $nombre = $doc->getClientOriginalName();
                $hoy_aux = date("y_m_d_h_i_s");
                $aux_nombre = $hoy_aux . "_" . $nombre;
                $ruta_documento = "CertificadosMaquinas/" . $aux_nombre;
                Storage::disk('local')->put($ruta_documento, fopen($doc, 'r+'));
                $calibracion->documento = $aux_nombre;
                $calibracion->update();
            }
            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al registrar la callibración");
        }
    }
    /**
     * Muestras las calibraciones de la maquina
     */
    public function Historial($maquina_id)
    {
        try
        {
            $historial = CalibracionMaquina::where("maquina_id", $maquina_id)->get();
            return $this->Success("historial", $historial);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * DEscargar certificado de calibración
     */
    public function DescargarCert($id)
    {
        try
        {
            $calibracion = CalibracionMaquina::find($id);
            $nombre = $calibracion->documento;
            $ruta = storage_path('app' . DIRECTORY_SEPARATOR . "CertificadosMaquinas" . DIRECTORY_SEPARATOR . $nombre);
            // Se coloca el archivo en una ruta local
            if (!file_exists($ruta))
            {
                return  "<h3>Archivo no encontrado</h3>";
            }
            $header = 'application/pdf';
            return response()->download($ruta, $nombre, [
                'Content-Type' => $header,
                'Content-Disposition' => 'inline; filename="' . $nombre . '"'
            ]);
        }
        catch (Exception $e)
        {
            echo "<h4>Error al descargar certificado</h4>";
        }
    }

    public function ObtenerMaquinas()
    {
        try
        {
            $maquinas = MaquinaSoldar::where("estado", 1)->orderBy("no_control")->get();
            return $this->Success("maquinas", $maquinas);
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
