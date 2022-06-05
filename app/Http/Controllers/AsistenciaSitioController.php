<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class AsistenciaSitioController extends Controller
{
    //
    public function get()
    {
      $user = Auth::user();

      $asistencias_sitio = \App\AsistenciaSitio::
      join('empleados AS e','e.id','registro_asistencia_sitio.empleado_id')
      ->join('proyectos AS p','p.id','registro_asistencia_sitio.proyecto_id')
      ->select('registro_asistencia_sitio.*',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"),
      'p.nombre_corto')
      ->where('registro_asistencia_sitio.empleado_registra_id',$user->empleado_id)
      ->get();

      return response()->json($asistencias_sitio);
    }

    public function save(Request $request)
    {
      try {
        $user = Auth::user();

        DB::beginTransaction();
        foreach ($request->empleado_asignado_id as $key => $value) {

          $asistencias_sitio = new \App\AsistenciaSitio();
          $asistencias_sitio->proyecto_id = $request->proyecto_id;
          $asistencias_sitio->supervisor_id = $request->supervisor_id;
          $asistencias_sitio->empleado_id = $value['id'];
          $asistencias_sitio->fecha = $request->fecha;
          $asistencias_sitio->empleado_registra_id = $user->empleado_id;
          Utilidades::auditar($asistencias_sitio, $asistencias_sitio->id);
          $asistencias_sitio->save();

          $controltiempo = new \App\Controltiempo();
          $controltiempo->fecha = $request->fecha;
          $controltiempo->proyecto_id = $request->proyecto_id;
          $controltiempo->empleado_asignado_id = $value['id'];
          $controltiempo->supervisor_id = $request->supervisor_id;
          Utilidades::auditar($controltiempo, $controltiempo->id);
          $controltiempo->save();

          $reg_checador = new \App\RegChecador();
          $reg_checador->empleado = $value['name'];
          $reg_checador->empleado_id = $value['id'];
          $reg_checador->fecha = $request->fecha;
          $reg_checador->hora = '08:00:00';
          $reg_checador->save();

          $reg_checador = new \App\RegChecador();
          $reg_checador->empleado = $value['name'];
          $reg_checador->empleado_id = $value['id'];
          $reg_checador->fecha = $request->fecha;
          $reg_checador->hora = '18:00:00';
          $reg_checador->save();
        }
        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => 'error']);
      }
    }

    public function delete($id)
    {
      try {
        DB::beginTransaction();
        $asistencias_sitio = \App\AsistenciaSitio::where('id',$id)->first();

        $controltiempo = \App\Controltiempo::where('empleado_asignado_id',$asistencias_sitio->empleado_id)
        ->where('proyecto_id',$asistencias_sitio->proyecto_id)
        ->where('fecha',$asistencias_sitio->fecha)
        ->where('supervisor_id',$asistencias_sitio->supervisor_id)
        ->delete();

        $reg_checador = \App\RegChecador::where('empleado_id',$asistencias_sitio->empleado_id)
        ->where('fecha',$asistencias_sitio->fecha)->delete();

        $asistencias_sitio_delete = \App\AsistenciaSitio::where('id',$id)->delete();
        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(['status' => 'error']);
      }
    }
}
