<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Empleado;
use Validator;

class PermisosEmpController extends Controller
{
  public function index(Request $request)
{
  $permisoem = Empleado::select('empleados.*',  DB::raw('CONCAT(empleados.nombre," ",empleados.ap_paterno," ",empleados.ap_materno) AS e_nombre'))
  ->get();
  return response()->json($permisoem);
}

public function busquedaPermisos(Request $request)
{
  $busqueda=DB::select("SELECT s.*, (CONCAT (ss.nombre,' ',ss.ap_paterno,' ',ss.ap_materno ))as sol_nom,(CONCAT (sss.nombre,' ',sss.ap_paterno,' ',sss.ap_materno)) as aut_nom FROM solicitud_permisos as s JOIN empleados as ss ON s.solicita_empleado_id = ss.id JOIN empleados as sss ON s.autoriza_empleado_id = sss.id WHERE (fecha_inicio IS NULL AND (fech_temp >= '$request->fecha_inicio' AND fech_temp <= '$request->fecha_fin')  OR fecha_inicio IS NOT NULL AND (fecha_inicio >= '$request->fecha_inicio' AND fecha_fin <='$request->fecha_fin')) AND solicita_empleado_id = '$request->empleados_id'");

  return response()->json($busqueda);
}

}
