<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;


class ControlTiempoQRController extends Controller
{
    //
    public function getProyecto(Request $request)
    {
      $data = DB::table('proyectos')->where('nombre_corto','LIKE','%'.$request->des.'%')
      ->select('id','nombre_corto')
      ->get();

      return response()->json($data);
    }

    public function guardar(Request $request)
    {
      try {
        $user = Auth::user();
        $ct = new \App\Controltiempo();
        $ct->supervisor_id = $user->empleado_id;
        $ct->empleado_asignado_id = $request->empleado_id;
        $ct->fecha  = $request->fecha;
        $ct->proyecto_id = $request->proyecto;
        $ct->nave = $request->nave;
        Utilidades::auditar($ct, $ct->id);
        $ct->save();

        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        Utilidades::errors();
      }
    }
}
