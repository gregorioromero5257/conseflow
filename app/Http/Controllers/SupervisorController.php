<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Auth;
use App\Repositories\Supervisor;




class SupervisorController extends Controller
{
    public function index (){

    $supervisor=DB::table('supervisores_empleado')
        ->Join('empleados as E','E.id','=','supervisores_empleado.supervisor_id')
        ->Join('empleados as EE','EE.id','=','supervisores_empleado.empleado_asignado_id')
        ->select('supervisores_empleado.*',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre"),DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS asignado_nombre"))
        ->get()->toArray();
    return response()->json($supervisor);

    }

    public function store (Request $request)
    {
            $supervisor_id = $request->supervisor_id['id'];
            $tamaño = count($request->empleado_asignado);
            $fecha_asignacion= date("Y-m-d");


        if($tamaño != 0){
            for ($i=0 ; $i < $tamaño ; $i++){
                $this->guardaEmpleadoAsignado(
                    $supervisor_id,
                    $request->empleado_asignado[$i]['id'],
                    $fecha_asignacion
                );
            }
        }
        return response()->json(array('status'=>true));
    }


    public function guardaEmpleadoAsignado ($supervisor_id,$empleado_asignado_id,$fecha_asignacion ){

        $guardaSupervisor = new \App\Supervisor();
        $guardaSupervisor->supervisor_id = $supervisor_id;
        $guardaSupervisor->empleado_asignado_id = $empleado_asignado_id;
        $guardaSupervisor->fecha_asignacion  = $fecha_asignacion;
        Utilidades::auditar($guardaSupervisor, $guardaSupervisor->id);
        $guardaSupervisor->save();
    }
}
