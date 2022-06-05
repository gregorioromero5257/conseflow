<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RequisicionEspejoController extends Controller
{
    //
    public function show($id)
    {

      $arreglo = [];
      $compras = Compras::where('conrequisicion','0')->where('proyecto_id',$id)->get();
      foreach ($compras as $key => $compra) {

        $requisicionhasordencompras = requisicionhasordencompras::where('orden_compra_id',$compra->id)
        ->select('requisicione_id','orden_compra_id')->get();
        $arreglo [] = [
          'compra' => $compra->folio,
          'partidas' => $requisicionhasordencompras[0],
          'key' => $key,
        ];
      }
      $arreglo_requisicion= [];
      foreach ($arreglo as $key => $value) {
        // code...
        $requisicion = Requisicion::orderBy('id', 'asc')
        ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
        ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
        ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
        ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
        ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
        ->select('requisiciones.*','p.nombre AS nombrep','p.nombre_corto as p_nombre_corto',
        DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_empleado_solicita"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"),
        DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombre_empleado_valida"),
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_empleado_recibe"))
        ->where('requisiciones.id','=',$value['partidas']->requisicione_id)
        // ->where('requisiciones.estado_id','!=','9')->where('requisiciones.estado_id','!=','11')
        ->first();
        $arreglo_requisicion [] = [
          $requisicion
        ];
      }
      return response()->json($arreglo_requisicion);
    }
}
