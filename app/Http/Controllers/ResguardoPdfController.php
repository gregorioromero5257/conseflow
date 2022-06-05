<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;

class ResguardoPdfController extends Controller
{
      public function pdf($id)
  {



    $count =1;
      $salidas = DB::table('salidasresguardo')->select('salidasresguardo.*',
      DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
      DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS supervisor"),
      DB::raw("CONCAT(ESS.nombre,' ',ESS.ap_paterno,' ',ESS.ap_materno) AS solicita"))
      ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidasresguardo.empleado_entrega_id')
      ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidasresguardo.empleado_supervisor_id')
      ->leftJoin('empleados AS ESS', 'ESS.id', '=', 'salidasresguardo.empleado_solicita_id')

      ->leftJoin('proyectos', 'proyectos.id', '=', 'salidasresguardo.proyecto_id')
      ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidasresguardo.tiposalida_id')
      ->where('salidasresguardo.id','=',$id)->first();
      $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();
      $partidas = DB::table('partidas')
      ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
      ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
      ->select('partidas.*','A.descripcion AS descripcion','A.unidad')
      ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();
      $valor = 30 - count($partidas);
      $partidascount =count($partidas);



      $pdf = PDF::loadView('pdf.resguardo', compact('salidas','partidas','partidascount','count','valor'));
      //  $pdf->setPaper('A4', 'landscape');
      $pdf->setPaper("A4", "portrait");
      // return $pdf->download('cv-interno.pdf');
      return $pdf->stream();



  }

}
