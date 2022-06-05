<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;

class EntradaInternaController extends Controller
{
    //

    public function store(Request $request)
    {
      try {
        DB::beginTransaction();
         $db_stock = DB::table('stocks AS s')->where('s.id',$request->stock)->first();

         $ordencompra = 1;
         if ($request->stock != 1) {
           $compra = \App\Compras::where('folio','LIKE','INV-EI-'.$db_stock->nombre)->first();
           if (isset($compra) == true) {
             $ordencompra = $compra->id;
           }else {
             $compra_new = new \App\Compras();
             $compra_new->folio = 'INV-EI-'.$db_stock->nombre;
             $compra_new->proyecto_id = $db_stock->proyecto_id;
             $compra_new->proveedore_id = 1;
             $compra_new->estado_id = 3;
             $compra_new->elabora_empleado_id = 6;
             $compra_new->autoriza_empleado_id = 205;
             $compra_new->condicion = 2;
             Utilidades::auditar($compra_new,$compra_new->id);
             $compra_new->save();

             $ordencompra = $compra_new->id;
           }
         }

        $rhoc = new \App\requisicionhasordencompras();
        $rhoc->requisicione_id = 1;
        $rhoc->orden_compra_id = $ordencompra;
        $rhoc->articulo_id = $request->articulo_id;
        $rhoc->cantidad = $request->cantidad;
        $rhoc->precio_unitario = 0;
        $rhoc->tipo_entrada = 'Interna';
        $rhoc->condicion = 0;
        $rhoc->antigua = 0;
        $rhoc->cantidad_entrada = 0;
        Utilidades::auditar($rhoc,$rhoc->id);
        $rhoc->save();

        $entradaid = 1;
        if ($request->stock != 1) {
          $entrada = \App\Entrada::where('comentarios','LIKE','INV-EI-'.$db_stock->nombre)->first();
          if (isset($entrada) == true) {
            $entradaid = $entrada->id;
          }else {
            $entrada_new = new \App\Entrada();
            $entrada_new->fecha = date('Y-m-d');
            $entrada_new->comentarios = 'INV-EI-'.$db_stock->nombre;
            $entrada_new->formato_entrada = '0001';
            $entrada_new->tipo_entrada_id = 1;
            $entrada_new->empleado_calidad_id = 3;
            $entrada_new->empleado_almacen_id = 3;
            $entrada_new->empleado_usuario_id = 147;
            $entrada_new->condicion = 1;
            $entrada_new->orden_compra_id = $ordencompra;
            Utilidades::auditar($entrada_new,$entrada_new->id);
            $entrada_new->save();

            $entradaid = $entrada_new->id;
          }
        }

        // PArtidaEntrada
        $partidaEntrada = new \App\PartidaEntrada();
        $partidaEntrada->entrada_id = $entradaid;
        $partidaEntrada->req_com_id = $rhoc->id;
        $partidaEntrada->articulo_id = $request->articulo_id;
        $partidaEntrada->validacion_calidad = 0;
        $partidaEntrada->cantidad = $request->cantidad;
        $partidaEntrada->almacene_id = 1;
        $partidaEntrada->pendiente = 0;
        $partidaEntrada->status = 0;
        $partidaEntrada->precio_unitario = 0;
        $partidaEntrada->stocke_id = $db_stock->id;
        $partidaEntrada->save();

        // Lote
        $lote = new \App\Lote();
        $lote->nombre = "lote 0002-".$request->articulo_id;
        $lote->entrada_id = $partidaEntrada->id;
        $lote->articulo_id = $request->articulo_id;
        $lote->cantidad = 1;
        $lote->save();
        $lote->nombre = ("lote 0002-" . $request->articulo_id . "-" . $lote->id);
        $lote->save();

        // LoteAlmacen
        $lote_almacen = new \App\LoteAlmacen();
        $lote_almacen->lote_id = $lote->id;
        $lote_almacen->almacene_id = 1;
        $lote_almacen->cantidad = $request->cantidad;
        $lote_almacen->stocke_id = $db_stock->id;
        $lote_almacen->articulo_id = $request->articulo_id;
        $lote_almacen->condicion = 1;
        $lote_almacen->codigo_barras = 'MCF 0001 '.$request->articulo_id.' '.$lote->id;
        $lote_almacen->save();

        // Existencia ??? Crear nueva existencia para cada articulo? (Repetidos?)
        $existencia = new \App\Existencia();
        $existencia->articulo_id = $request->articulo_id;
        $existencia->almacene_id = 1;
        $existencia->id_lote = $lote->id;
        $existencia->cantidad = $request->cantidad;
        $existencia->save();

        // Movimiento
        $movimiento = new \App\Movimiento();
        $movimiento->cantidad = $request->cantidad;
        $movimiento->fecha = date("y-m-d");
        $movimiento->hora = date("H:i:s");
        $movimiento->tipo_movimiento = "INV";
        $movimiento->folio = "Entrada-" . 1 . 1;
        $movimiento->proyecto_id = 1;
        $movimiento->lote_id = $lote_almacen->id;
        $movimiento->stocke_id = $db_stock->id;
        $movimiento->almacene_id = 1;
        $movimiento->articulo_id = $request->articulo_id;
        $movimiento->save();

        $stock_articulo = \App\StockArticulo::where("articulo_id", "=", $request->articulo_id)
            ->where("stocke_id", "=", $db_stock->id)->first();
        if ($stock_articulo == null)
        {
            // Registrar nuevo
            $nuevo_stock = new \App\StockArticulo();
            $nuevo_stock->cantidad = $request->cantidad;
            $nuevo_stock->articulo_id = $request->articulo_id;
            $nuevo_stock->stocke_id = $db_stock->id;
            $nuevo_stock->save();
        }
        else
        {
            // Sumar cantidad
            $n = $stock_articulo->cantidad + $request->cantidad;
            $stk = \App\StockArticulo::where("articulo_id", "=", $request->articulo_id)
                ->where("stocke_id", "=", $db_stock->id)->first();
            $stk->cantidad = $n;
            $stk->update();
        }

        DB::commit();
        return response()->json(['status' => true]);

      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
        return response()->json(["status"=>false,"mensaje"=>"Error al generar entrada interna"]);
      }
    }
}
