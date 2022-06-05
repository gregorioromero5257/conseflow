<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidasOrdenCompraGasto;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;



class PartidasOrdenCompraGastoController extends Controller
{

    /**
    * Busca las partidas de la tabla
    */
    public function show($id)
    {
      // $pocg = PartidasOrdenCompraGasto::
      // leftJoin('articulos')
      // ->where('orden_compra_gasto_id',$id)->get();

      $compras_vehi = [];

      $compras_art = DB::table('partidas_orden_compra_gasto')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'partidas_orden_compra_gasto.orden_compra_gasto_id')
      ->leftJoin('articulos AS a', 'a.id', '=', 'partidas_orden_compra_gasto.articulo_id')
      ->select('partidas_orden_compra_gasto.*',
      DB::raw('partidas_orden_compra_gasto.cantidad * partidas_orden_compra_gasto.precio_unitario as total'),
      'a.id AS aid',
      'a.descripcion AS ad'
      )
      ->where('partidas_orden_compra_gasto.orden_compra_gasto_id', '=', $id)
      ->where('partidas_orden_compra_gasto.articulo_id','!=','null')
      ->get()->toArray();

      $compras_serv = DB::table('partidas_orden_compra_gasto')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'partidas_orden_compra_gasto.orden_compra_gasto_id')
      ->leftJoin('servicios AS s', 's.id', '=', 'partidas_orden_compra_gasto.servicio_id')
      ->select('partidas_orden_compra_gasto.*',
      DB::raw('partidas_orden_compra_gasto.cantidad * partidas_orden_compra_gasto.precio_unitario as total'),
      's.id AS aid',
      's.nombre_servicio AS ad'
      )
      ->where('partidas_orden_compra_gasto.orden_compra_gasto_id', '=', $id)
      ->where('partidas_orden_compra_gasto.servicio_id','!=','null')
      ->get()->toArray();

      // if ($proyecto->nombre_corto === 'MANTENIMIENTO VEHICULAR') {
      //   $compras_vehi = DB::table('requisicion_has_ordencompras')
      //   ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      //   ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      //   ->leftJoin('cat_mantenimiento_vehiculos AS v', 'v.id', '=', 'requisicion_has_ordencompras.vehiculo_id')
      //   ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      //   ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      //   ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      //   'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      //   'v.id AS aid','v.descripcion AS ad','CPS.catalogo_centro_costos_id')
      //   ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      //   ->where('requisicion_has_ordencompras.vehiculo_id','!=','null')
      //   ->get()->toArray();
      //   // $compras = array_merge($compras_art,$compras_serv,$compras_vehi);
      //
      // }elseif ($proyecto->nombre_corto === 'VEHÍCULOS') {
      //   $compras_vehi = DB::table('requisicion_has_ordencompras')
      //   ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      //   ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      //   ->leftJoin('vehiculos AS v', 'v.id', '=', 'requisicion_has_ordencompras.vehiculo_id')
      //   ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      //   ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      //   ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      //   'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      //   'v.id AS aid','v.descripcion AS ad','CPS.catalogo_centro_costos_id')
      //   ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      //   ->where('requisicion_has_ordencompras.vehiculo_id','!=','null')
      //   ->get()->toArray();
      //   // $compras = array_merge($compras_art,$compras_serv,$compras_vehi);
      //
      // }

      $compras = array_merge($compras_art,$compras_serv);

      // $total = $this->ObtenerTotal($id);
      // $ordenCompra = \App\Compras::findOrFail($id);
      // $ordenCompra->total = $total;
      // $ordenCompra->total_aux = (floatval(str_replace(',','',$total)));
      // $ordenCompra->save();

      return response()->json($compras);


      // return response()->json($pocg);
    }

    public function create(Request $request)
    {
      try {
        $this->compraEC($request);
        return response()->json(array('status' => true));
      } catch (\Exception $e) {
        Utilidades::errors($e);
        return response()->json(array('status' => 'error'));
      }
    }

    public function compraEC($request)
    {
      try{
      if ($request->vehiculo_id == 0 && $request->mantenimiento_v_id == 0) {
        $valor_ = null;
      }elseif ($request->vehiculo_id == 0 && $request->mantenimiento_v_id != 0) {
        $valor_ = $request->mantenimiento_v_id;
      }elseif ($request->vehiculo_id != 0 && $request->mantenimiento_v_id == 0) {
        $valor_ = $request->vehiculo_id;
      }
      $requi = new PartidasOrdenCompraGasto();
      $requi->orden_compra_gasto_id = $request->orden_compra_id;
      $requi->articulo_id = $request->articulo_id == 0 ? null : $request->articulo_id;
      $requi->servicio_id = $request->servicio_id == 0 ? null : $request->servicio_id;
      $requi->vehiculo_id = $valor_;
      // $requi->vehiculo_id = $request->vehiculo_id == 0 ? null : $request->vehiculo_id;
      $requi->cantidad = $request->cantidad;
      $requi->precio_unitario = $request->precio_unitario;
      $requi->comentario = $request->comentario;
      $requi->cantidad_entrada = $request->cantidad;
      Utilidades::auditar($requi, $requi->pda);
      $requi->save();

      return true;
       } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function delete($id)
    {
      $pocg = PartidasOrdenCompraGasto::where('id',$id)->delete();
      return response()->json(['status' => true]);
    }

}
