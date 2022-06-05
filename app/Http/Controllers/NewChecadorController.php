<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NewChecadorController extends Controller
{
    /**
    ** Funcion crear un registro del checador QR y en la sabana de tiempo si se cumplen las condiciones
    ** funciona sin que el usuario este autentificado,
    **/
    public function store(Request $request)
    {
        try {

            $a = new \App\RegChecador();
            $a->empleado = $request->empleado;
            $a->empleado_id = $request->empleado_id;
            $a->fecha = $request->fecha;
            $a->hora = $request->hora;
            $a->save();

            $user = Auth::user();
            //Se evalua si existe un usuario
            if (isset($user) == true) {
                // Se evalua que el usuario tenga un empleado asignado
                if ($user->empleado_id != null) {

                    $proyecto_supervisor = DB::table('proyecto_supervisor_sitio')
                    ->where('empleado_id',$user->empleado_id)
                    ->first();

                    $proyecto = 0;
                    if (isset($proyecto_supervisor) == false) {
                        $proyecto = 1;
                    }else {
                        $proyecto = $proyecto_supervisor->proyecto_id;
                    }
                    //Se evalua que no exista un registro anterio del empleado para el mismo dia de no se asi,
                    // se crea un nuevo registro.
                    $ct = \App\Controltiempo::where('empleado_asignado_id',$request->empleado_id)
                    ->where('fecha',$request->fecha)
                    ->where('proyecto_id',$proyecto)
                    ->first();

                    if (isset($ct) == false) {
                        $guardaSupervisor = new \App\Controltiempo();
                        $guardaSupervisor->supervisor_id = $user->empleado_id;
                        $guardaSupervisor->empleado_asignado_id = $request->empleado_id;
                        $guardaSupervisor->fecha  = $request->fecha;
                        $guardaSupervisor->proyecto_id = $proyecto;
                        $guardaSupervisor->horas_extras = 0;
                        $guardaSupervisor->save();
                    }
                }
            }


            return response()->json(['status' => true]);
        } catch (\Throwable $e) {
            Utilidades::errors($e, 2);
        }
    }
}
