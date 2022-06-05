<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\PolizaUnidades;
use Barryvdh\DomPDF\Facade as PDF;


class AsignacionVehiculoController extends Controller
{
    //
    public function listadoEmpleado (Request $request)
  {
    $listado = DB::table('empleados')
    ->select('empleados.*')
    ->get();

    return response()->json($listado);
  }

  public function resguardovehiculo (Request $request)
{
  $resguardo = PolizaUnidades::where('poliza_unidades.id','=',$id)
  ->leftJoin('unidades','unidades.id','=','poliza_unidades.unidad_id')
  ->select('poliza_unidades.*','unidades.modelo as modelo','unidades.numero_serie as num_serie','unidades.marca as marca',
  'unidades.modelo as modelo','unidades.placas as placas', 'unidades.numero_tarjeta_circulacion as tarjeta_circulacion')
  ->get()->first();

  return response()->json($resguardo);
}

  public function pdfcne($id)
  {
    $resguardo = PolizaUnidades::where('poliza_unidades.id','=',$id)
    ->leftJoin('unidades','unidades.id','=','poliza_unidades.unidad_id')
    ->leftJoin('caracteristicas_vehiculo','caracteristicas_vehiculo.unidad_id','=','unidades.id')
    ->select('poliza_unidades.*','unidades.modelo as modelo','unidades.numero_serie as num_serie','unidades.marca as marca',
    'poliza_unidades.numero_poliza as numero_poliza','caracteristicas_vehiculo.numero_puertas as puertas','caracteristicas_vehiculo.clave_vehicular as clave',
    'caracteristicas_vehiculo.capacidad as capacidad','caracteristicas_vehiculo.numero_motor as motor','caracteristicas_vehiculo.colores as colores',
    'caracteristicas_vehiculo.cilindros as cilindros','caracteristicas_vehiculo.transporte as transporte',
    'unidades.modelo as modelo','unidades.placas as placas', 'unidades.numero_tarjeta_circulacion as tarjeta_circulacion')
    ->get()->first();

          $dias = array("Domingo","Lunes","Martes","Miercoles","Jueves","Viernes","Sábado");
          $meses = array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
          $hoy = $dias[date('w')]." ".date('d')." de ".$meses[date('n')-1]. " del ".date('Y') ;
          $count = 1;

          $pdfcne = PDF::loadView('pdf.valeResguardo', compact('resguardo','hoy'));
          $pdfcne->setPaper("letter","portrait");

          return $pdfcne->stream();

  }
}
