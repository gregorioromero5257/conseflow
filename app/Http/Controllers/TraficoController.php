<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class TraficoController extends Controller
{
    //
    public function descargarInv()
    {
      $arreglo = [];
      $data = DB::table('unidades')->get();

      foreach ($data as $key => $value) {
        $fecha = '';
        $compania = '';
        $num_poliza = '';
        $inicio_p = '';
        $termino_p = '';
        $vehiculo = DB::table('vehiculos AS v')->where('descripcion','LIKE','%'.$value->placas.'%')->first();
        if (isset($vehiculo) == true) {
          $rhoc = DB::table('requisicion_has_ordencompras AS rhoc')
          ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
          ->select('oc.fecha_orden')
          ->where('rhoc.vehiculo_id',$vehiculo->id)
          ->first();
          $fecha = $rhoc->fecha_orden;

          $seguro = DB::table('poliza_unidades')->where('unidad_id',$value->id)
          ->orderBy('id','DESC')
          ->first();

          if (isset($seguro) == true) {
            $compania = $seguro->proveedor;
            $num_poliza = $seguro->numero_poliza;
            $inicio_p = $seguro->fecha_inicio;
            $termino_p = $seguro->fecha_termino;
          }
        }

        $arreglo [] = [
          'unidad' => $value,
          'fecha_ad' => $fecha,
          'compania' => $compania,
          'poliza' => $num_poliza,
          'inicio_p' => $inicio_p,
          'termino_p' => $termino_p,
        ];
      }

      $pdf = PDF::loadView('pdf.inventariovehiculos', compact('arreglo'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      // $pdf->setPaper('letter', 'portrait');
      $pdf->setPaper('ledger', 'portrait');
      return $pdf->stream();
    }
}
