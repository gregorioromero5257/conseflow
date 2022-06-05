<?php
namespace App\Http\Controllers;

date_default_timezone_set('America/Mexico_City');

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Requisicion;
use App\Proyecto;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;
use File;



class RequisicioncomController extends Controller
{
   /**
   * [Consulta en BD los registros de la tabla requisiciones en relacion al id proporcionado]
   * @param  Int      $id [id del GET]
   * @return Response     [Array en formato JSON]
   */
  public function show($id)
  {
    $requisicioncom = Requisicion::orderBy('id', 'asc')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
    ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
    ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
    ->select('requisiciones.*','p.nombre AS nombrep',
    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_empleado_solicita"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"),
    DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombre_empleado_valida"),
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_empleado_recibe"))
    ->where('requisiciones.proyecto_id','=',$id)->where('requisiciones.estado_id','=','5')
    ->get();
    return response()->json($requisicioncom);
  }
}
