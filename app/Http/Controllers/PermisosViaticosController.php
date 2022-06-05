<?php

namespace App\Http\Controllers;

use App\Empleado;
use App\Http\Controllers\Controller;
use App\Http\Helpers\Utilidades;
use App\PermisoViatico;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PermisosViaticosController extends Controller
{

    /**
     * Obtiene todos los permisosode viaticos registrados
     */
    public function index()
    {
        try
        {
            $permisos = DB::table("permisos_viaticos as pv")
                ->join("empleados as e", "e.id", "pv.propietario_id")
                ->join("empleados as e2", "e2.id", "pv.empleado_permitido_id")
                ->select(
                    "pv.id",
                    "pv.empleado_permitido_id",
                    "pv.propietario_id",
                    "pv.empresa",
                    DB::raw("CONCAT_WS(' ',e.nombre,e.ap_paterno,e.ap_materno) as propietario"),
                    DB::raw("CONCAT_WS(' ',e2.nombre,e2.ap_paterno,e2.ap_materno) as permitido")
                )
                ->orderBy("empresa")
                ->orderBy("propietario")
                ->orderBy("permitido")
                ->get();

            return response()->json(["status" => true, "permisos" => $permisos]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    /**
     * Crea un nuevo permiso para vizualización de vaticos
     */
    public function store(Request $request)
    {
        try
        {
            $existe = PermisoViatico::where("propietario_id", $request->propietario_id)
                ->where("empleado_permitido_id", $request->empleado_permitido_id)
                ->where("empresa", $request->empresa)
                ->first();
            if ($existe == null)
            {
                $permiso = new PermisoViatico($request->all());
                $permiso->save();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }

    public function ObtenerEmpleados()
    {
        try
        {
            $empleados = Empleado::where("condicion", 1)
                ->select(
                    "id",
                    DB::raw("CONCAT_WS(' ',nombre,ap_paterno,ap_materno) as nombre")
                )
                ->orderBy("nombre")
                ->get();
            //$emmp_csc=EmpleadoDBP::get();
            // $empledos = array_merge($emmp_coser);
            return response()->json(["status" => true, "empleados" => $empleados]);
        }
        catch (Exception $e)
        {
            Utilidades::errors($e);
            return response()->json(["status" => false]);
        }
    }
}
