<?php

namespace App\Http\Controllers;

use App\Empleado;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\Proyecto;
use App\Supervisor;
use App\SupervisorProyecto;
use Exception;
use PhpParser\Node\Stmt\Else_;

class SupervisorProyectoController extends Controller
{
    public function ObtenerProyectos()
    {
        try
        {
            $proyectos = DB::table("proyectos as p")
                ->leftJoin("supervisores_proyectos as sp", "sp.proyecto_id", "p.id")
                ->leftJoin("empleados as e", "e.id", "sp.supervisor_id")
                ->where("p.condicion", 1)
                ->select(
                    "sp.id as sp_id",
                    "sp.activo as sp_activo",
                    "p.id as p_id",
                    "p.nombre as p_nombre",
                    "e.id as sup_id",
                    DB::raw('concat_ws(" ",e.nombre, e.ap_paterno, e.ap_materno) as sup_nombre')
                )
                ->orderBy("p_nombre")
                ->get();
            return response()->json(["status" => true, "proyectos" => $proyectos]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }
    // Obtiene los supervisores 
    public function ObtenerSupervisores()
    {
        try
        {
            $proyectos = Empleado::where("condicion", "1")
                ->select("id", DB::raw("concat_ws(' ',nombre,ap_paterno,ap_materno) as nombre"))
                ->orderBy("nombre")->get();
            return response()->json(["status" => true, "empleados" => $proyectos]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    // Activar o desactivar el supervisor de proyecto
    public function CambiarEstado(Request $request)
    {
        try
        {
            $super = SupervisorProyecto::find($request->id);
            if ($super == null)
                return response()->json(["status" => false, "mensaje" => "Supervisor no encontrado"]);

            $super->activo = $request->estado;
            $super->save();
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }

    // Guardar supervisor
    public function GuardarSuper(Request $request)
    {
        try
        {
            if ($request->id_super_proyecto == null)
            {
                // Craear nuevo registro
                $super = new SupervisorProyecto();
                $super->proyecto_id = $request->proy_id;
                $super->supervisor_id = $request->sp_id;
                $super->activo = 1;
                $super->save();
            }
            else
            {
                // Reasignar
                $super = SupervisorProyecto::find($request->id_super_proyecto);
                $super->proyecto_id = $request->proy_id;
                $super->supervisor_id = $request->sp_id;
                $super->activo = 1;
                $super->save();
            }
            return response()->json(["status" => true]);
        }
        catch (Exception $e)
        {
            return response()->json(["status" => false, "mensaje" => $e->getMessage()]);
        }
    }
}
