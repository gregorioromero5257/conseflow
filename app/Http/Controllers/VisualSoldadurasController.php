<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Http\Request;

class VisualSoldadurasController extends Controller
{
    //

    public function pdf($id){

      $inspeccion = \App\Inspeccion::join('servicios_inspeccion AS SI','SI.id','=','inspeccion.elemento_servicios_id')
      ->join('especimen AS E','E.id','=','inspeccion.especimen_id')
      ->join('datos_examinacion AS DE','DE.id','=','inspeccion.datos_examinacion_id')
      ->select('inspeccion.*','SI.nombre AS nombre_si','E.nombre AS nombre_e','E.especificacion_material','DE.distancia_al_objeto',
      'angulo_aprox_superficie','condicion_superficial')
      ->where('inspeccion.id',$id)
      ->first();

      $partidas = \App\PartidasInspeccion::join('soldadores AS S','S.id','=','partidas_inspeccion.id_soldador')
      ->join('materiales_servicios_sol AS MSS','MSS.id','=','partidas_inspeccion.materiales_servicios_sol_id')
      ->select('partidas_inspeccion.*','S.num','MSS.soldadura','MSS.material_uno AS material_uno_n',
      'MSS.material_dos AS material_dos_n')
      ->where('partidas_inspeccion.inspeccion_id',$id)->get();

      $partida = \App\PartidasInspeccion::select('partidas_inspeccion.*')->where('inspeccion_id',$id)->get();
      $tamanio = count($partida);

      $totalpaginas = ceil($tamanio/9);

      $totalfinal = ($totalpaginas * 9) - $tamanio;

        $pdf = PDF::loadView('pdf.visual', compact('visual','inspeccion','partidas','tamanio','totalpaginas','totalfinal'));
        return $pdf->stream();
    }
}
