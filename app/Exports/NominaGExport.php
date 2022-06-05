<?php
namespace App\Exports;

use App\User;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use \App\Http\Helpers\Utilidades;



class NominaGExport implements FromView, ShouldAutoSize
{
  protected $id;

  public function __construct(string $id)
  {
    $this->id = $id;
  }

  public function view(): View
  {
    $nomina = [];
    $suma = [];
    $arreglo = [];
    $valores= explode('&',$this->id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $proyecto_id = $valores[2];
    $condiciontrestexto = '';
    $tipo = $valores[3];
    $condicion = 0;
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id == 0) {
      $condicion = 1;

      if ($tipo == 1) {
        $nominas = DB::table('nomina')->select('nomina.*')->get();
        foreach ($nominas as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS suma') )->where('nominas_movimientos.nomina_id','=',$value->id)->first();
          $arreglo [] =
          [
            'nominas' => $value,
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS suma'))->first();
      }

      if ($tipo == 2) {
        $nominas = DB::table('nomina')->select('nomina.*')->get();
        foreach ($nominas as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.transferencias AS valor','nominas_movimientos.totales',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS suma') )->where('nominas_movimientos.nomina_id','=',$value->id)->first();
          $arreglo [] =
          [
            'nominas' => $value,
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS suma') )->first();
      }

    }
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $tipo == 0) {
      $condicion = 2;
      $nomina = DB::table('nomina')->select('nomina.*')->get();
      foreach ($nomina as $key => $value) {
        $nomina = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
        )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
        $sumauno = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
        ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
        if (count($nomina) != 0) {
          $arreglo [] =
          [
            'nominas' => $value,
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        else {
          $condicion = 99;
          break;
        }
      }
      $suma = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
      ->where('P.id','=',$proyecto_id)->first();
    }
    ////////////////////
    if ($proyecto_id == 0 && $tipo == 0) {
      $condicion = 3;
      $nomina = DB::table('nomina')->select('nomina.*')
      ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
      ->get();
      foreach ($nomina as $key => $value) {
        $nomina = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
        )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
        $sumauno = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
        ->where('nominas_movimientos.nomina_id','=',$value->id)->first();
        $arreglo [] = [
          'nominas' => $value,
          'movimientos' => $nomina,
          'suma' => $sumauno,
        ];
      }
      $suma = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
      ->first();
      $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
      ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
    }
    /////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id !=0 && $tipo == 0) {
      $condicion = 4;
      $nomina = DB::table('nomina')->select('nomina.*')
      ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
      ->get();
      foreach ($nomina as $key => $value) {
        $nomina = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
        )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
        $sumauno = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
        ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
        $arreglo [] = [
          'nominas' => $value,
          'movimientos' => $nomina,
          'suma' => $sumauno,
        ];
      }
      $suma = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
      ->where('P.id','=',$proyecto_id)->first();

      $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
      ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
    }
    // /////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id == 0 && $tipo != 0) {
      $condicion = 5;
      if ($tipo == 1) {//efectivos
        $nomina = DB::table('nomina')->select('nomina.*')
        ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
        ->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->first();
          $arreglo [] = [
            'nominas' => $value,
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
        ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
        ->first();

        $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
        ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
      }
      if ($tipo == 2) {//transferencias
        $nomina = DB::table('nomina')->select('nomina.*')
        ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
        ->w->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->first();
          $arreglo [] = [
            'nominas' => $value,
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg') )
        ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
        ->first();

        $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
        ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
      }

    }
    // /////////////////////
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id !=0 && $tipo != 0) {
      $condicion = 6;
      if ($tipo == 1) {//efectivos
        $nomina = DB::table('nomina')->select('nomina.*')
        ->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'nominas' => $value,
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
            break;
          }

        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
        ->where('P.id','=',$proyecto_id)
        ->first();

      }
      if ($tipo == 2) {//transferencias
        $nomina = DB::table('nomina')->select('nomina.*')
        ->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'nominas' => $value,
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
            break;
          }
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg') )
        ->where('P.id','=',$proyecto_id)->first();

      }
    }
    //////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id != 0 && $tipo != 0) {
      $condicion = 7;

      if ($tipo == 1) {//efectivos
        $nomina = DB::table('nomina')->select('nomina.*')
        ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
        ->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'nominas' => $value,
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
            break;
          }
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg') )
        ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
        ->where('P.id','=',$proyecto_id)
        ->first();

        $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
        ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
      }
      if ($tipo == 2) {//transferencias
        $nomina = DB::table('nomina')->select('nomina.*')
        ->whereBetween('fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('fecha_inicial',[$fecha_inicial, $fecha_final])
        ->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS suma') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'nominas' => $value,
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
            break;
          }
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg') )
        ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
        ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
        ->where('P.id','=',$proyecto_id)->first();

        $condiciontrestexto = substr($fecha_inicial,8,2).' de '.Utilidades::meses(substr($fecha_inicial,5,2)).' del '.substr($fecha_inicial,0,4).
        ' al '.substr($fecha_final,8,2).' de '.Utilidades::meses(substr($fecha_final,5,2)).' del '.substr($fecha_final,0,4);
      }

    }
    //////////////////////
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id == 0 && $tipo == 0) {
      $condicion = 8;

        $nomina = DB::table('nomina')->select('nomina.*')->get();
        foreach ($nomina as $key => $value) {
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('nominas_movimientos.nomina_id','=',$value->id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumae'),DB::raw('SUM(nominas_movimientos.transferencias) AS sumat') )
          ->where('nominas_movimientos.nomina_id','=',$value->id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'nominas' => $value,
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
            break;
          }
        }
        $suma = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumaeg'),DB::raw('SUM(nominas_movimientos.transferencias) AS sumatg') )
        ->first();

    }




    return view('excel.nominaq', compact('condicion','nomina','tipo','suma','arreglo','fecha_inicial','fecha_final','condiciontrestexto'));
  }
}
