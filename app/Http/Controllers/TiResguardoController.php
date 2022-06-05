<?php

namespace App\Http\Controllers;

use App\Empleado;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\TiRed;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use TiResguardo;


class TiResguardoController extends Controller
{

    /**
     * Obtiene los resguardos registrados
     */
    public function Obtener()
    {
        try
        {

            return $this->Success();
        }
        catch (Exception $e)
        {
            return $this->Error($e, "Error al obtener los resguardos");
        }
    }


    /***
     * Registra un nuevo vale de resguardo
     */
    public function Guardar(Request $request)
    {
        try
        {
            // return $this->Success();
            // $resguardo=new TiResguardo($request->all());
            // $resguardo->save();

        }
        catch(Exception $e)
        {
            return $this->Error($e,"Error al guardar el resguardo");
        }
    }

    /**
     * Obtiene todos los equipos registrados del tipo indicado
     */
    public function ObtenerEquipos($tipo)
    {
        try
        {
            // $tablas = ["ti_computo", "ti_video", "ti_impresoras", "ti_accesorios"];
            // $tabla = $tablas[$tipo - 1];
            // $campos = "";
            // if ($tipo == 1)
            //     $campos = "marca_modelo as marca,marca_modelo as modelo,no_serie, 
            //     concat('NS: ',no_serie,' - CPU: ',cpu,' - RAM: ',ram,' - HDD: ',almacenamiento,
            //     ' - MAC: ',mac,' ',observaciones) as caracteristicas,
            //     concat('NS: ',no_serie,' - CPU: ',cpu,' - RAM: ',ram,
            //     ' - MAC: ',mac) as nombre";
            // else if ($tipo == 2)
            //     $campos = "'-' as marca, '-' as modelo, no_serie,descripcion as caracteristicas,
            //     concat('NS: ',no_serie,' - ',descripcion) as nombre";
            // else
            //     $campos = "marca,modelo, no_serie,descripcion as caracteristicas,
            //     concat(descripcion, ' NS: ',no_serie) as nombre";

            // $equipos = DB::table($tabla)
            //     ->where("condicion", 1)
            //     ->select("id", DB::raw($campos))
            //     ->get();
            // return $this->Success("equipos", $equipos);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    /**
     * Obtiene todos los empleados que tienen un resguardo
     */
    public function ObtenerEmpleados()
    {
        try
        {
            $empleados = DB::table("empleados")
                ->select("id", DB::raw("concat_ws(' ',nombre,ap_paterno,ap_materno) as nombre"))
                ->where("condicion", 1)
                ->orderBy("nombre")
                ->get();
            return $this->Success("empleados", $empleados);
        }
        catch (Exception $e)
        {
            return $this->Error($e);
        }
    }

    public function Success($nombre = "", $data=[])
    {
        return response()->json(["status" => true, $nombre => $data]);
    }

    public function Error($e, $mensaje = "Error al obtener los datos")
    {
        Utilidades::errors($e);
        return response()->json(["status" => false, "mensaje" => $mensaje]);
    }
}
