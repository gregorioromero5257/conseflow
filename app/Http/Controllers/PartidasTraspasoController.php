<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidasTraspasos;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Validator;
use Illuminate\Validation\Rule;


class PartidasTraspasoController extends Controller
{
  // protected $rules = array(
  //     'cantidad' => 'required',
  //     'calidad' => 'required',
  // );

  public function index()
  {
    // code...
  }

  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $partida = \App\PartidasTraspasos::where('lote_almacen_id','=',$request->lote_almacen_id)->where('traspaso_id','=',$request->traspaso_id)->first();
    if ($partida == null) {
      $partida_traspaso = new PartidasTraspasos();
      $partida_traspaso->fill($request->all());
      $partida_traspaso->save();
    }
    else {
      $partida_traspaso = \App\PartidasTraspasos::where('lote_almacen_id','=',$request->lote_almacen_id)->where('traspaso_id','=',$request->traspaso_id)->first();
      $partida_traspaso->cantidad = $partida_traspaso->cantidad + floatval($request->cantidad);
      $partida_traspaso->save();
    }

    $lote_almacen = \App\LoteAlmacen::where('id','=',$partida_traspaso->lote_almacen_id)->first();
    $lote_almacen->cantidad = $lote_almacen->cantidad - floatval($request->cantidad);
    $lote_almacen->save();

    $existencias = \App\Existencia::where('id_lote','=',$lote_almacen->lote_id)->first();
    $existencias->cantidad = $existencias->cantidad - floatval($request->cantidad);
    $existencias->save();

    $stoke_art = \App\StockArticulo::where('articulo_id','=',$lote_almacen->articulo_id)
    ->where('stocke_id','=',$lote_almacen->stocke_id)->first();
    $stoke_art->cantidad = $stoke_art->cantidad - floatval($request->cantidad);
    $stoke_art->save();

    return response()->json(array('status' => true));
  }

  public function show($id)
  {
    $partida_traspaso = DB::table('partidas_traspasos')
    ->leftJoin('lote_almacen AS LA','LA.id','=','partidas_traspasos.lote_almacen_id')
    ->leftJoin('lotes AS L','L.id','=','LA.lote_id')
    ->leftJoin('almacenes AS AL','AL.id','=','LA.almacene_id')
    ->leftJoin('tipo_ubicacion AS TU','TU.id','=','AL.ubicacion_id')
    ->leftJoin('niveles AS N','N.id','=','LA.nivel_id')
    ->leftJoin('stands AS ST','ST.id','=','LA.stand_id')
    ->leftJoin('stocks AS S','S.id','=','LA.stocke_id')
    ->leftJoin('proyectos AS P','P.id','=','S.proyecto_id')
    ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
    ->select('partidas_traspasos.*','TU.nombre AS nombre_ubicacion','S.nombre AS nombre_stock','A.nombre AS anombre','A.codigo AS acodigo',
    'A.descripcion AS adescripcion','A.marca AS amarca','A.unidad AS aunidad')
    ->where('partidas_traspasos.traspaso_id','=',$id)
    ->where('partidas_traspasos.cantidad','>','0')
    ->get();
    return response()->json($partida_traspaso);
  }

  public function corregirpartidatraspaso(Request $request){
    // $cantidad_nueva = $request->cantidad_existente -
    $partida_traspaso = \App\PartidasTraspasos::where('id','=',$request->id)->first();
    $partida_traspaso->cantidad = $partida_traspaso->cantidad - $request->cantidad;
    $partida_traspaso->save();

    $lote_almacen = \App\LoteAlmacen::where('id','=',$partida_traspaso->lote_almacen_id)->first();
    $lote_almacen->cantidad = $lote_almacen->cantidad + $request->cantidad;
    $lote_almacen->save();

    $existencias = \App\Existencia::where('id_lote','=',$lote_almacen->lote_id)->first();
    $existencias->cantidad = $existencias->cantidad + $request->cantidad;
    $existencias->save();

    $stoke_art = \App\StockArticulo::where('articulo_id','=',$lote_almacen->articulo_id)
    ->where('stocke_id','=',$lote_almacen->stocke_id)->first();
    $stoke_art->cantidad = $stoke_art->cantidad + $request->cantidad;
    $stoke_art->save();

    return response()->json(array('status' => true));
  }

  public function eliminarpartidatraspaso($id){
    $partida_traspaso = \App\PartidasTraspasos::where('id','=',$id)->first();
    // $partida_traspaso->cantidad = $partida_traspaso->cantidad - $request->cantidad;
    // $partida_traspaso->save();

    $lote_almacen = \App\LoteAlmacen::where('id','=',$partida_traspaso->lote_almacen_id)->first();
    $lote_almacen->cantidad = $lote_almacen->cantidad + $partida_traspaso->cantidad;
    $lote_almacen->save();

    $existencias = \App\Existencia::where('id_lote','=',$lote_almacen->lote_id)->first();
    $existencias->cantidad = $existencias->cantidad + $partida_traspaso->cantidad;
    $existencias->save();

    $stoke_art = \App\StockArticulo::where('articulo_id','=',$lote_almacen->articulo_id)
    ->where('stocke_id','=',$lote_almacen->stocke_id)->first();
    $stoke_art->cantidad = $stoke_art->cantidad + $partida_traspaso->cantidad;
    $stoke_art->save();

    $partida_traspaso->delete();

    return response()->json(array('status' => true));

  }


}
