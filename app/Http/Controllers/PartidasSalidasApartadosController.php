<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class PartidasSalidasApartadosController extends Controller
{

public function store(Request $request)
{
  $partida = \App\Partidas::where('salida_id','=',$request->salida_id)
  ->where('lote_id','=',$request->lote_id)->first();
  if ($partida != null) {
    $partidas = \App\Partidas::where('salida_id','=',$request->salida_id)->where('lote_id','=',$request->lote_id)->first();
    $partidas->salida_id = $request->salida_id;
    $partidas->tiposalida_id = $request->tiposalida_id;
    $partidas->lote_id = $request->lote_id;
    $partidas->cantidad = $partidas->cantidad + $request->cantidad;
    $partidas->save();
  }
  else {
    $partidas = new \App\Partidas();
    $partidas->fill($request->all());
    $partidas->save();
  }

  $tipo_salida_nombre = \App\TipoSalida::where('id','=',$request->tiposalida_id)->first();

  $lote_temporal = \App\LoteTemporal::where('id','=',$request->lote_temporal_id)->first();
  $cantidadresta = $lote_temporal->cantidad - $request->cantidad;
  $lote_temporal->cantidad = $cantidadresta;
  $lote_temporal->update();

  $lote_almacen = \App\LoteAlmacen::where('id','=',$lote_temporal->lote_almacen_id)->first();

  $stokearticulo = \App\StockArticulo::where('articulo_id','=',$lote_almacen->articulo_id)->where('stocke_id','=',$lote_almacen->stocke_id)->first();
  $cantidadrestastoke = $stokearticulo->cantidad - $request->cantidad;
  $stokearticulo->cantidad = $cantidadrestastoke;
  $stokearticulo->update();

  $existencias = \App\Existencia::where('id_lote','=',$lote_almacen->lote_id)->where('articulo_id','=',$lote_almacen->articulo_id)->first();
  $cantidadrestaexistencia = $existencias->cantidad - $request->cantidad;
  $existencias->cantidad = $cantidadrestaexistencia;
  $existencias->update();

  $stocks = \App\Stock::where('id','=',$stokearticulo->stocke_id)->first();
  $proyectos = \App\Proyecto::where('id','=',$stocks->proyecto_id)->first();

  $hoy = date("Y-m-d");
  $hora = date("H:i:s");
  $movimiento = new \App\Movimiento();
  $movimiento->cantidad = $request->cantidad;
  $movimiento->fecha = $hoy;
  $movimiento->hora = $hora;
  $movimiento->tipo_movimiento = 'Salida ';
  $movimiento->folio = 'Salida-'.$lote_almacen->articulo_id.' a '.$tipo_salida_nombre->nombre;
  $movimiento->proyecto_id = $proyectos->id;
  $movimiento->lote_id =  $lote_almacen->id;
  $movimiento->stocke_id =  $stokearticulo->stocke_id;
  $movimiento->almacene_id = $lote_almacen->almacene_id;
  $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null:$lote_almacen->stand_id;
  $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null:$lote_almacen->nivel_id;
  $movimiento->articulo_id = $lote_almacen->articulo_id;
  $movimiento->save();

  //return response()->json($stokearticulo);
  return response()->json(array('status' => true));
}

}
