<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
Use Validator;
use \App\Http\Helpers\Utilidades;
use Illuminate\Validation\Rule;



class NominaQuincenalBController extends Controller
{
  public function busqueda($id)
  {

    $nomina = [];
    $suma = [];
    $arreglo = [];
    $valores= explode('&',$id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $proyecto_id = $valores[2];
    $condiciontrestexto = '';
    $tipo = $valores[3];
    $condicion = 0;
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id == 0) {
      $condicion = 1;

      if ($tipo == 1) {

          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
          ->where('C.tipo_nomina_id','=','2')->first();
          $arreglo [] =
          [
            'condicion' => '1',
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];

      }

      if ($tipo == 2) {

          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','P.nombre AS nombrep','nominas_movimientos.transferencias','nominas_movimientos.transferencias AS valor','nominas_movimientos.totales',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat') )
          ->where('C.tipo_nomina_id','=','2')->first();
          $arreglo [] =
          [
            'condicion' => '1',
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];

      }
    }
    //////////////////////
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $tipo == 0) {
      $condicion = 2;

        $nomina = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
        )->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->get();
        $sumauno = DB::table('nominas_movimientos')
        ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
        ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
        ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
        ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
        ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
        ->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->first();
        if (count($nomina) != 0) {
          $arreglo [] =
          [
            'condicion' => '2',
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
        }
        else {
          $condicion = 99;
        }


    }
    ////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id == 0 && $tipo == 0) {
      $condicion = 3;

      $nomina = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
      )->where('C.tipo_nomina_id','=','2')
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
      ->get();
      $sumauno = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
      ->where('C.tipo_nomina_id','=','2')
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])
      ->first();
      $arreglo [] = [
        'condicion' => '3',
        'movimientos' => $nomina,
        'suma' => $sumauno,
      ];
      // }

    }
    /////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id !=0 && $tipo == 0) {
      $condicion = 4;
      $nomina = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select('nominas_movimientos.*','P.nombre AS nombrep',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
      )->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->get();
      $sumauno = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
      ->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)
      ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
      ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->first();
      $arreglo [] = [
        'condicion' => '4',
        'movimientos' => $nomina,
        'suma' => $sumauno
      ];
    }
    // /////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id == 0 && $tipo != 0) {
      $condicion = 5;
      if ($tipo == 1) {//efectivos
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')
          ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
          ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
          ->where('C.tipo_nomina_id','=','2')
          ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
          ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->first();
          $arreglo [] = [
            'condicion' => '5',
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
      }
      if ($tipo == 2) {//transferencias
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.transferencias','P.nombre AS nombrep',
          'nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre"))
          ->where('C.tipo_nomina_id','=','2')
          ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
          ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat') )
          ->where('C.tipo_nomina_id','=','2')
          ->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
          ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->first();
          $arreglo [] = [
            'condicion' => '5',
            'movimientos' => $nomina,
            'suma' => $sumauno,
          ];
      }
    }
    // /////////////////////
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id !=0 && $tipo != 0) {
      $condicion = 6;
      if ($tipo == 1) {//efectivos
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
          ->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'condicion' => '6',
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
          }
      }
      if ($tipo == 2) {//transferencias
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.transferencias',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat') )
          ->where('C.tipo_nomina_id','=','2')->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'condicion' => '6',
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
          }
      }
    }
    //////////////////////
    if ($fecha_inicial != 'null' && $fecha_final != 'null' && $proyecto_id != 0 && $tipo != 0) {
      $condicion = 7;

      if ($tipo == 1) {//efectivos
          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
            ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
          ->where('C.tipo_nomina_id','=','2')->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
            ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'condicion' => '7',
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
          }
      }
      if ($tipo == 2) {//transferencias

          $nomina = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.transferencias',
          'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
          DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
          )->where('C.tipo_nomina_id','=','2')->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
            ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->where('P.id','=',$proyecto_id)->get();
          $sumauno = DB::table('nominas_movimientos')
          ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
          ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
          ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
          ->leftJoin('nomina AS N','N.id','=','nominas_movimientos.nomina_id')
          ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat') )
          ->where('C.tipo_nomina_id','=','2')->whereBetween('N.fecha_final',[$fecha_inicial, $fecha_final])
            ->whereBetween('N.fecha_inicial',[$fecha_inicial, $fecha_final])->where('P.id','=',$proyecto_id)->first();
          if (count($nomina) != 0) {
            $arreglo [] =
            [
              'condicion' => '7',
              'movimientos' => $nomina,
              'suma' => $sumauno,
            ];
          }
          else {
            $condicion = 99;
          }
    }
  }
    ///////////////////////////////////////////////////////////
    if ($fecha_inicial == 'null' && $fecha_final == 'null' && $proyecto_id == 0 && $tipo == 0) {

      $nomina = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->select('nominas_movimientos.dias_trabajados','nominas_movimientos.efectivos','nominas_movimientos.transferencias',
      'P.nombre AS nombrep','nominas_movimientos.efectivos AS valor','nominas_movimientos.totales',
      DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS e_nombre")
      )->where('C.tipo_nomina_id','=','2')->get();
      $sumauno = DB::table('nominas_movimientos')
      ->leftJoin('empleados AS E','E.id','=','nominas_movimientos.empleado_id')
      ->leftJoin('contratos AS C','C.id','=','nominas_movimientos.contrato_id')
      ->leftJoin('proyectos AS P','P.id','=','nominas_movimientos.proyecto_id')
      ->select(DB::raw('SUM(nominas_movimientos.transferencias) AS sumat'),DB::raw('SUM(nominas_movimientos.efectivos) AS sumae') )
      ->where('C.tipo_nomina_id','=','2')->first();
      if (count($nomina) != 0) {
        $arreglo [] =
        [
          'condicion' => '8',
          'movimientos' => $nomina,
          'suma' => $sumauno,
        ];
      }
      else {
        $condicion = 99;

      }

    }

    return response()->json($arreglo);
  }

}
