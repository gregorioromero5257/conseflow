<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cuadrilla;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class CuadrillaController extends Controller
{
    //

    public function index(Request $request)
    {
      $cuadrilla = DB::table('cuadrilla_trabajo')
      ->leftJoin('empleados as es','es.id','=','cuadrilla_trabajo.supervisor')
      ->leftJoin('empleados as ea','ea.id','=','cuadrilla_trabajo.empleados')
      ->leftJoin('puestos','puestos.id','=','ea.puesto_id')
      ->leftJoin('proyectos','proyectos.id','=','cuadrilla_trabajo.proyecto_id')
      ->select('cuadrilla_trabajo.*','proyectos.nombre_corto as nombre_proyecto','puestos.nombre as puesto',
      DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS asignado_nombre"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS supervisor_nombre"))
      ->get();

      return response()->json($cuadrilla);

    }

public function store(Request $request)
    {
        try{
        $status = true;
        $supervisor_id = $request->supervisor_id['id'];
        $tamaño = count($request->empleado_asignado_id);
        $fecha= $request->fecha;
        $proyecto = $request->proyecto_id;



        if($tamaño != 0){
            for ($i=0 ; $i < $tamaño ; $i++) {

                $comparar = DB::table('cuadrilla_trabajo')
                    ->select('cuadrilla_trabajo.empleados','cuadrilla_trabajo.fecha')
                    ->where('cuadrilla_trabajo.empleados','=', $request->empleados[$i]['id'])
                    ->where('cuadrilla_trabajo.fecha','=',$request->fecha)
                    ->first();

                if (isset($comparar)) {
                    return response()->json(array(
                        'status' => false,
                        'tipo' => 'empleado duplicado',
                        'error' => true,
                    ));

                    $status = false;
                }
            }
        }

       if($status){

           if($tamaño != 0){
               for ($i=0 ; $i < $tamaño ; $i++){
                   $this->guardaEmpleadoAsignado(
                       $supervisor_id,
                       $request->empleado_asignado_id[$i]['id'],
                       $fecha,
                       $proyecto

                   );
               }
           }
           return response()->json(array('status'=>true));
       }
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function guardaEmpleadoAsignado ($supervisor_id,$empleado_asignado_id,$fecha,$proyecto){
        try{

        $guardaSupervisor = new \App\Cuadrilla();
        $guardaSupervisor->supervisor = $supervisor_id;
        $guardaSupervisor->empleados = $empleado_asignado_id;
        $guardaSupervisor->fecha  = $fecha;
        $guardaSupervisor->proyecto_id = $proyecto;


        Utilidades::auditar($guardaSupervisor, $guardaSupervisor->id);
        $guardaSupervisor->save();
         } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

    }

    public function comparaEmpleado(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules);

        $comparar = DB::table('control_tiempo')
            ->select('control_tiempo.empleado_asignado_id')
            ->get()->toArray();

        if ($comparar == $request->empleado_asignado_id) {
            return response()->json(array(
                'status' => false,
                'errors' => $validator->errors()->all()
            ));
        }
        else{
            return response()->json(array('status'=>true));
    }
    }

}
