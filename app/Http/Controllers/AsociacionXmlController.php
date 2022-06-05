<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;

class AsociacionXmlController extends Controller
{
    /**
    ** obtiene las ordenes compra que tiene factura y se calcula el porcentanje de partidas asociadas
    *  @param int id proyecto
    **/
    public function getOcs($id)
    {
      $ordenes_compra = DB::table('facturas_entradas AS fe')
      ->join('ordenes_compras AS oc','oc.id','fe.orden_compra_id')
      ->join('proveedores AS p','p.id','oc.proveedore_id')
      ->where('fe.tipo','1')
      ->where('fe.orden_compra_id','!=',NULL)
      ->where('oc.proyecto_id',$id)
      ->select('oc.id','oc.folio','oc.proyecto_id','oc.fecha_orden','p.razon_social','oc.total','oc.moneda')
      ->groupBy('oc.id')
      ->orderBy('oc.fecha_orden','DESC')
      ->get();

      $arreglo = [];

      foreach ($ordenes_compra as $key => $value) {
        $con_asociacion = DB::table('requisicion_has_ordencompras')
        ->where('orden_compra_id',$value->id)
        ->where('asociada','1')
        ->count();

        $total = DB::table('requisicion_has_ordencompras')
        ->where('orden_compra_id',$value->id)
        ->count();

        $total_avance = ($con_asociacion * 100) /$total;

        $arreglo [] =[
            'data' => $value,
            'porcentaje' => $total_avance
        ];
      }

      return response()->json($arreglo);
    }

    public function getProyectos()
    {
      $data = DB::table('facturas_entradas AS fe')
      ->join('ordenes_compras AS oc','oc.id','fe.orden_compra_id')
      ->join('proyectos AS p','p.id','oc.proyecto_id')
      ->where('fe.tipo','1')
      ->where('fe.orden_compra_id','!=',NULL)
      ->select('p.id','p.nombre_corto')
      ->groupBy('p.id')
      ->get();

      return response()->json($data);
    }

    public function getPartidas($id)
    {
      $compras_art = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->join('partidas_requisiciones', function ($join){
        $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
        ->on('requisicion_has_ordencompras.articulo_id','=','partidas_requisiciones.articulo_id');
      })
      ->select('requisicion_has_ordencompras.*',
      DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),'req.id AS rid',
      'req.folio AS rf','partidas_requisiciones.comentario',
      'req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      'a.id AS aid','a.descripcion AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.articulo_id','!=','null')
      ->get()->toArray();

      $compras_serv = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('servicios AS s', 's.id', '=', 'requisicion_has_ordencompras.servicio_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->join('partidas_requisiciones', function ($join){
        $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
        ->on('requisicion_has_ordencompras.servicio_id','=','partidas_requisiciones.servicio_id');
      })
      ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      's.id AS aid','partidas_requisiciones.comentario','s.nombre_servicio AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.servicio_id','!=','null')
      ->get()->toArray();

      $compras = array_merge($compras_art,$compras_serv);

      return response()->json($compras);
    }

    public function getConceptos($id)
    {
      $data = DB::table('conceptos_oc_xml AS cox')
      ->join('gastos_xml_oc AS gxo','gxo.id','cox.gastos_xml_oc_id')
      ->select('cox.*')
      ->where('gxo.ordenes_compras_gastos_id',$id)
      ->where('cox.partida_rhc_id','=',null)
      ->get();

      return response()->json($data);
    }

/**
**
**/
    public function guardarAsociacion(Request $request)
    {
      try {
        DB::beginTransaction();
        // GUARDA EL ID DE LA  PARTIDA DE LA ORDEN DE COMPRA
        $concepto = \App\ConceptosOCXml::where('id',$request->concepto_xml)->first();
        $concepto->partida_rhc_id = $request->partida;
        Utilidades::auditar($concepto, $concepto->id);
        $concepto->save();

        //CAMBIA A EL ESTADO DE LA PARTIDA
        $rhoc = \App\requisicionhasordencompras::where('id',$request->partida)->first();
        $rhoc->asociada = 1;
        Utilidades::auditar($rhoc, $rhoc->id);
        $rhoc->save();

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }
}
