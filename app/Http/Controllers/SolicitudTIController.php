<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\ImagenSolicitud;
use App\SolicitudTI;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SolicitudTIController extends Controller
{

    protected $PATH_ROOT = "Solicitudes/";
    /**
     **** TIPOS ****
     * 1. Falla de equipo
     * 2. Ayuda del ERP
     * 3. Conexión a Internet
     * 4. Otro
     */

    /**
     **** ESTADOS ***
     * 1. Enviado
     * 2. Recibido
     * 3. En proceso
     * 4. Terminado
     * 5. Cancelado
     */

    /**
     * Registra una nueva solicitud por parte del usuario
     */
    public function Registrar(Request $request)
    {
        try
        {
            DB::beginTransaction();
            if (!$request->ajax()) redirect("/");

            // Crear soli
            $soli = new SolicitudTI($request->all());
            $soli->fecha_hora_recibido = date("Y-m-d H:i:s");
            $soli->save();
            Utilidades::auditar($soli, $soli->id);

            // Archivos
            $imagenes = $request->archivos;
            $i = 1;
            foreach ($imagenes as $imagen)
            {
                $nombre = $imagen["upload"]["filename"];
                $data64 = $imagen["dataURL"];
                $extension = explode('/', explode(':', substr($data64, 0, strpos($data64, ';')))[1])[1];
                $data64 = str_replace('data:image/' . $extension . ';base64,', '', $data64);
                // $data64 = str_replace('data:image/jpg;base64,', '', $data64);
                $data64 = str_replace(' ,', '+', $data64);
                $ruta_imagen = $this->PATH_ROOT;
                $ruta_full = $ruta_imagen  . $nombre;
                if (!file_exists($ruta_imagen))
                    mkdir($ruta_imagen, 0777, true);
                Storage::disk('local')->put($ruta_full, base64_decode($data64));
                $i++;

                $imagen_soli = new ImagenSolicitud();
                $imagen_soli->ruta = $nombre;
                $imagen_soli->solicitud_id = $soli->id;
                $imagen_soli->save();
            }
            DB::commit();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            DB::rollBack();
            Utilidades::errors($e);
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    /**
     * Obtiene las solicitudes hechas por el usuario actual
     */
    public function Obtener()
    {
        $usuario = Auth::user();
        $solicitudes = SolicitudTI::where("usuario_id", $usuario->id)->get();
        return response()->json(["solicitudes" => $solicitudes, "status" => true]);
    }

    /**
     * Obtiene todas las solicitudes realizadas
     */
    public function ObtenerTodas($tipo)
    {
        /**
         * 1. Todas
         * 2. ERP
         * 3. Intenet, etc
         */

        $op = "=";
        $n = "2";
        if ($tipo == 1)
        {
            $op = "!=";
            $n = "0";
        }
        else if ($tipo == 2)
        {
            $op = "=";
            $n = "2";
        }
        else
        {
            $op = "!=";
            $n = "2";
        }
        if ($tipo == 3) "tipo != 0";
        if ($tipo == 2) "tipo = 2";
        if ($tipo == 1) "tipo != 2";
        $solicitudes = DB::table("ti_solicitudes as ts")
            ->join("users as u", "u.id", "ts.usuario_id")
            ->join("empleados as e", "e.id", "u.empleado_id")
            ->where("tipo_id", $op, $n)
            ->select(
                "ts.*",
                DB::raw('concat_ws(" ",e.nombre, e.ap_paterno,e.ap_materno) as nombre_empleado')
            )->get();
        return response()->json(["status" => true, "solicitudes" => $solicitudes]);
    }

    /**
     * Cambia el estado de la solicitud
     */
    public function CambiarEstado(Request $request)
    {
        try
        {
            $solicitud = SolicitudTI::find($request->id);
            $solicitud->estado_id = $request->estado_id;
            if ($request->estado_id >= 4)
            {
                $solicitud->solucion = $request->mensaje;
                $solicitud->fecha_hora_terminado = date("Y-m-d H:i:s");
            }
            $solicitud->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Cambia la prioridad de la solicitud
     */
    public function AsignarPrioridad(Request $request)
    {
        try
        {
            $solicitud = SolicitudTI::find($request->id);
            $solicitud->prioridad = $request->prioridad;
            $solicitud->update();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Obtiene las imagenes de la solicitud ingresada
     */
    public function CargarImagenes($id)
    {
        try
        {
            $imagenesSoli = ImagenSolicitud::where("solicitud_id", $id)->get();
            $imagenes = [];
            foreach ($imagenesSoli as $imagen)
            {
                $nombre = $imagen->ruta;
                $archivo = $this->ftpSolucionT($imagen->ruta);
                // Se coloca el archivo en una ruta local
                Storage::disk('temporal')->put($imagen->ruta, $archivo);
                array_push($imagenes, "temp/" . $nombre);
            }
            return response()->json(['status' => true, "imagenes" => $imagenes]);
        }
        catch (\Throwable $e)
        {
            return response()->json(["status" => false, "ERROR FATAL. Contacte al administrador"]);
            dd($e);
        }
    }

    public function ftpSolucionT($id)
    {
        //Se busca en disk o carpeta
        if (Storage::exists($this->PATH_ROOT . $id))
        {
            // Se coloca el archivo en una ruta local
            $archivo = Storage::disk('local')->get($this->PATH_ROOT . $id);
        }
        else
        {
            $output = shell_exec("mkdir /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/" . $this->PATH_ROOT . "/ 2> errormk.txt;cp /home/vsftpuser/ftp/Solicitudes/$id /var/www/html/conserflow2020/conserflow-sistema2018/storage/app/" . $this->PATH_ROOT . "/ 2> error.txt;");
            $archivo = Storage::disk('local')->get($this->PATH_ROOT . $id);
        }
        return $archivo;
    }

    /**
     * Obtiene los empleados del sistema
     */
    public function Empleados()
    {

        try
        {
            // Todos los empleados
            $usuarios = DB::table("users as u")
                ->join("empleados as e", "e.id", "u.empleado_id")
                ->where("e.condicion", 1)
                ->select(
                    "u.id as id",
                    DB::raw('concat_ws(" ",e.nombre, e.ap_paterno,e.ap_materno) as nombre')
                )
                ->get();
            // ACtual
            $actual = DB::table("users as u")
                ->join("empleados as e", "e.id", "u.empleado_id")
                ->where("u.id", Auth::user()->id)
                ->select(
                    "u.id as id",
                    DB::raw('concat_ws(" ",e.nombre, e.ap_paterno,e.ap_materno) as nombre')
                )
                ->first();
            return response()->json(["status" => true, "listaUsuarios" => $usuarios, "usuario" => $actual]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }
}
