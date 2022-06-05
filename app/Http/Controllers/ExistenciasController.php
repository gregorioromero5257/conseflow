<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Almacene;
use App\Categoria;
use App\Existencia;
use App\Exports\ExistenciaGeneralExport;
use App\Nivele;
use App\Stand;
use App\TipoUbicacion;
use App\Exports\ExistenciaSExport;
use App\Exports\ExistenciaMesExport;
use App\Exports\ExistenciaProyectoExport;
use Maatwebsite\Excel\Facades\Excel;
use \App\Http\Helpers\Utilidades;
use Exception;
use Illuminate\Support\Facades\DB;





class ExistenciasController extends Controller
{

  public function index(Request $request)
  {
    $ubicacion = Utilidades::ubicacion();
  }

  public function nivelIndex()
  {
    // code...
    $nivel = Nivele::select('niveles.*')
      ->get();
    return response()->json($nivel);
  }

  public function standIndex()
  {
    // code...
    $stand = Stand::select('stands.*')
      ->get();
    return response()->json($stand);
  }
  public function ubicacionIndex()
  {
    // code...
    $ubicacion = TipoUbicacion::select('tipo_ubicacion.*')
      ->get();
    return response()->json($ubicacion);
  }

  public function edit($id)
  {
    // code...
    $existencias = Existencia::findOrFail($id);
    if ($existencias->condicion == 0)
    {
      $existencias->condicion = 1;
    }
    else
    {
      $existencias->condicion = 0;
    }
    $DatosBancEmpleado->update();
    return $DatosBancEmpleado;
  }

  public function getList()
  {
    $exx = Almacene::select('almacenes.*', 'tipo_ubicacion.nombre as ubicacion')
      ->leftJoin('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'almacenes.ubicacion_id')
      ->orderBy('id', 'desc')->get()->toArray();
    return response()->json($exx);
  }

  public function getListStand(Request $request, $id)
  {
    $ex = Stand::select('id', 'nombre')
      ->where('almacene_id', $id)
      ->orderBy('id', 'desc')
      ->get()
      ->toArray();
    return response()->json($ex);
  }

  public function getListNivel(Request $request, $id)
  {
    $exnivel = Nivele::select('id', 'nombre')
      ->where('stande_id', $id)
      ->orderBy('id', 'desc')
      ->get()
      ->toArray();
    return response()->json($exnivel);
  }

  public function excel($id)
  {
    $dts = explode("&", $id);
    if (count($dts) != 2)
      return response()->json(["status" => false, "mensaje" => "Datos incompletos"]);

    $nombre = "";
    if ($dts[0] == 0)
    {
      // sin almacen
      $cat = Categoria::find($dts[1]);
      $nombre = "Existencia: - " . $cat->nombre . ".xlsx";
    }
    else
    {
      // sin categoria

      $alm = Almacene::find($dts[0]);
      $nombre = "Existencia: - " . $alm->nombre . ".xlsx";
    }

    return Excel::download(new ExistenciaSExport($id), $nombre);
  }

  public function excelMes($id)
  {
    try
    {
      $valores = explode('&', $id);
      $nombre = 'ReporteMesual' . $valores[0] . '-' . $valores[1] . '.xlsx';

      return Excel::download(new ExistenciaMesExport($id), $nombre);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function excelProyecto($id)
  {
    try
    {
      $proyecto = DB::table('proyectos')->where('id', $id)->first();

      $nombre = 'ReporteProyecto' . str_replace(' ', '', $proyecto->nombre_corto) . '.xlsx';

      return Excel::download(new ExistenciaProyectoExport($id), $nombre);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function excelGeneral()
  {
    try
    {
      return Excel::download(new ExistenciaGeneralExport(),"Existencia General.xlsx");
    }
    catch (Exception $e)
    {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }
}
