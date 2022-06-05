<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;

class LoteAlmacenController extends Controller
{
    //
    public function cambiarEntrada(Request $request)
    {
      try {

      DB::beginTransaction();

      $lote_almacen = \App\LoteAlmacen::where('id', $request->lote_almacen_id)->first();
      $lote_almacen->cantidad = (float)$request->cantidad;
      Utilidades::auditar($lote_almacen, $lote_almacen->id);
      $lote_almacen->save();

      $lotes = \App\Lote::where('id',$lote_almacen->lote_id)->first();
      $lotes->cantidad = (float)$request->cantidad;
      Utilidades::auditar($lotes, $lotes->id);
      $lotes->save();

      $partida_entrada = \App\PartidaEntrada::where('id',$lotes->entrada_id)->first();
      $partida_entrada->cantidad = (float)$request->cantidad;
      Utilidades::auditar($partida_entrada, $partida_entrada->id);
      $partida_entrada->save();

      $rhoc = \App\requisicionhasordencompras::where('id',$partida_entrada->req_com_id)->first();
      if ($rhoc->condicion == 0) {
        $rhoc->cantidad_entrada = (float)$rhoc->cantidad - (float)$request->cantidad;
        $rhoc->condicion = 1;
        Utilidades::auditar($rhoc, $rhoc->id);
        $rhoc->save();
      }else {
        $rhoc->cantidad_entrada = (float)$rhoc->cantidad - (float)$request->cantidad;
        Utilidades::auditar($rhoc, $rhoc->id);
        $rhoc->save();
      }

      $existencias = \App\Existencia::where('id_lote', $lotes->id)->first();
      $existencias->cantidad = (float)$request->cantidad;
      Utilidades::auditar($existencias, $existencias->id);
      $existencias->save();

      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $movimiento = new \App\Movimiento();
      $movimiento->cantidad = (float) $request->cantidad;//
      $movimiento->fecha = $hoy;
      $movimiento->hora = $hora;
      $movimiento->tipo_movimiento = 'Update';
      $movimiento->folio = 'Correcion-0000';
      $movimiento->proyecto_id =  $request->proyecto_id;//
      $movimiento->lote_id =  $lote_almacen->id;//
      $movimiento->stocke_id =  $lote_almacen->stocke_id;//
      $movimiento->articulo_id = $lote_almacen->articulo_id;//
      Utilidades::auditar($movimiento, $movimiento->id);
      $movimiento->save();

      $stoke_art = DB::table('stock_articulos')->select('stock_articulos.*')
        ->where('stock_articulos.articulo_id', '=', $lote_almacen->articulo_id)
        ->where('stock_articulos.stocke_id', '=', $lote_almacen->stocke_id)
        ->first();

      $cantida_n = ((float)$stoke_art->cantidad > (float)$rhoc->cantidad) ?
        (float)$stoke_art->cantidad - ((float)$request->cantidad_original - (float) $request->cantidad) :
        $request->cantidad;

      DB::table('stock_articulos')
        ->where([
          ['articulo_id',  '=',  $lote_almacen->articulo_id],
          ['stocke_id', '=', $lote_almacen->stocke_id],
        ])
        ->update(['cantidad' => $cantida_n]);
        DB::commit();
        return response()->json(array(
          'status' => true
        ));
      }
      catch (\Throwable $e){
        DB::rollBack();
        Utilidades::errors($e);
      }

    }

    /**
     * [activ Actualiza la cantidad en la tabla stock_articulos de la BD]
     * @param  Int $id [description]
     * @return Response  [status => true]
     */
    public function eliminarPartidaEntrada($id)
    {
      try
      {

        DB::beginTransaction();

        $stock_articulo_id = 0;

        $lote_almacen = \App\LoteAlmacen::where('id', $id)->first();
        $lote = \App\Lote::where('id', $lote_almacen->lote_id)->first();
        $partida_entrada = \App\PartidaEntrada::where('id', $lote->entrada_id)->first();
        $existencias = \App\Existencia::where('id_lote', $lote->id)->first();
        // return response()->json($existencias);
        $requisis = \App\Requisicionhasordencompras::where('id', $partida_entrada->req_com_id)->first();
        $requisis->cantidad_entrada = $requisis->cantidad_entrada + $lote->cantidad;
        $requisis->condicion = 1;
        $requisis->save();

        $hoy = date("Y-m-d");
        $hora = date("H:i:s");
        $movimiento = new \App\Movimiento();
        $movimiento->cantidad = (float) $partida_entrada->cantidad;
        $movimiento->fecha = $hoy;
        $movimiento->hora = $hora;
        $movimiento->tipo_movimiento = 'Delete';
        $movimiento->folio = 'Eliminacion-0000';
        $movimiento->proyecto_id =  0;
        $movimiento->lote_id =  $lote_almacen->id;
        $movimiento->stocke_id =  $lote_almacen->stocke_id;
        $movimiento->articulo_id = $partida_entrada->articulo_id;
        Utilidades::auditar($movimiento, $movimiento->id);
        $movimiento->save();

        $stoke_art = DB::table('stock_articulos')->select('stock_articulos.*')
          ->where('stock_articulos.articulo_id', '=', $partida_entrada->articulo_id)->where('stock_articulos.stocke_id', '=', $lote_almacen->stocke_id)
          ->first();

        $cantida_n =  (float)$stoke_art->cantidad -  (float)$partida_entrada->cantidad;;

        DB::table('stock_articulos')
          ->where([
            ['articulo_id',  '=',  $partida_entrada->articulo_id],
            ['stocke_id', '=', $lote_almacen->stocke_id],
          ])
          ->update(['cantidad' => $cantida_n]);

          $existencias_delete = \App\Existencia::where('id', $existencias->id)->first();
          $existencias_delete->delete();
          $partida_entrada_delete = \App\PartidaEntrada::where('id', $partida_entrada->id)->first();
          $partida_entrada_delete->delete();
          $lote_delete = \App\Lote::where('id', $lote->id)->first();
          $lote_delete->delete();
          $lote_almacen_delete = \App\LoteAlmacen::where('id', $lote_almacen->id)->first();
          $lote_almacen_delete->delete();

        DB::commit();
        return response()->json(array(
          'status' => true
        ));
      }
      catch (\Throwable $e)
      {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    /**
     * [condicion Actualiza el campo condicion en la tabla requisicion_has_ordencompras de la BD]
     * @param  Int $orden_compra_id [description]
     * @return Boolean              [description]
     */
    public function condicion_eliminar($req_com_id, $cantidad)
    {
      $requisis = \App\Requisicionhasordencompras::where('id', '=', $req_com_id)->first();
      $requisis->cantidad_entrada = $requisis->cantidad_entrada + $cantidad;
      $requisis->condicion = 1;
      $requisis->save();
      return true;
    }
}
