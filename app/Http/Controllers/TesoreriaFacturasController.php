<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Exports\MaterialesFacturaExport;
use Maatwebsite\Excel\Facades\Excel;

class TesoreriaFacturasController extends Controller
{
    /**
    ** Obtiene las partidas de las facturas que cuenten con una asociacion de partida de orden de compra
    **/
    public function getPartidasFacturas()
    {
        $partidas = DB::table('conceptos_oc_xml AS cox')
        ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','cox.partida_rhc_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('proyectos AS p','p.id','oc.proyecto_id')
        ->select('cox.id','p.nombre_corto','oc.folio','cox.claveprodserv','cox.descripcion','cox.claveunidad','cox.unidad',
        'cox.cantidad','cox.valorunitario','cox.importe')
        ->where('partida_rhc_id','!=',null)
        ->get();

        return response()->json($partidas);
    }

    /**
    ** Obtiene las partidas de las facturas que cuenten con una asociacion de partida de orden de compra
    **/
    public function getPartidasFacturasLI($id)
    {
      $partidas = DB::table('partidas AS pa')
      ->join('salidas AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id',$id)
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();
      // return response()->json($partidas);

      $partidas_r = DB::table('partidas AS pa')
      ->join('salidassitio AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id',$id)
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();

      $par_uno = array_merge($partidas, $partidas_r);

      $partidas_s = DB::table('partidas AS pa')
      ->join('salidasresguardo AS s','s.id','pa.salida_id')
      ->join('lote_almacen AS la','pa.lote_id','la.id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->where('pa.tiposalida_id','1')
      ->where('s.proyecto_id',$id)
      ->select('la.id','la.stocke_id',
      DB::raw("SUM(pa.cantidad) AS cantidad_salida"),
      'a.descripcion','a.unidad','l.id AS l_id','pe.id AS pe_di','pe.req_com_id'
      )
      ->groupBy('la.id')
      ->get()->toArray();

      $par = array_merge($par_uno, $partidas_s);

      $arreglo = [];

      foreach ($par as $key => $value) {
        $impuestos = [];
        $folio = '';
        $oc_num = '';
        $comentario = '';

          $partidas_facturas = DB::table('conceptos_oc_xml AS cox')
          ->join('gastos_xml_oc AS gxo','gxo.id','cox.gastos_xml_oc_id')
          ->join('ordenes_compras AS oc','oc.id','gxo.ordenes_compras_gastos_id')
          ->join('proveedores AS p','p.id','oc.proveedore_csct_id')
          ->select('cox.*','gxo.moneda','oc.folio','oc.proyecto_id','p.razon_social')
          ->where('cox.partida_rhc_id',$value->req_com_id)->first();

          $proyecto_origen = DB::table('stocks AS s')
          ->join('proyectos AS p','p.id','s.proyecto_id')
          ->select('p.nombre_corto')
          ->where('s.id',$value->stocke_id)
          ->first();

          $ordencompra = DB::table('requisicion_has_ordencompras AS rhoc')
          ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
          ->where('rhoc.id',$value->req_com_id)
          ->select('oc.folio','rhoc.comentario')
          ->first();
          if (isset($ordencompra) == true) {
            $folio = explode('-',$ordencompra->folio);
            $oc_num = $folio[count($folio) - 1];
            $comentario = $ordencompra->comentario;
          }

          if (isset($partidas_facturas) == true) {
            $impuestos_oc = DB::table('impuestos_oc_xml AS iox')
            ->where('iox.base',$partidas_facturas->importe)
            ->where('iox.base','!=',null)
            ->get();

            $impuestos = $impuestos_oc;
          }

          $arreglo [] = [
            'proyecto_origen' => $proyecto_origen->nombre_corto,
            'orden_compra' => $oc_num,
            'comentario' => $comentario ,
            'salida' => $value,
            'factura' => $partidas_facturas,
            'impuestos' => $impuestos,
          ];

      }

        return response()->json($arreglo);
    }

    public function descargarMateriales($id)
    {
      return Excel::download(new MaterialesFacturaExport($id), 'Materiales.xlsx' );

    }
}
