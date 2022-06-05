<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use Auth;

class SalidaResguardoController extends Controller
{
    //

    public function getLotes(Request $request)
    {
      try {
        $lotes = \App\LoteAlmacen::
        join('articulos AS a','a.id','lote_almacen.articulo_id')
        ->join('stocks AS s','s.id','lote_almacen.stocke_id')
        ->join('proyectos AS p','p.id','s.proyecto_id')
        ->where(function ($query) use ($request)
        {
          $query->Where('a.descripcion', 'LIKE', '%' . $request->des . '%')
          ->orWhere('a.nombre','LIKE','%'.$request->des.'%')
          ->orWhere('a.codigo','LIKE','%'.$request->des.'%')
            ;
            })
        ->where('lote_almacen.cantidad','>','0')
        ->select('lote_almacen.*','a.descripcion','a.nombre','a.codigo','a.marca',
        'a.unidad','p.nombre_corto')
        ->get();

        return response()->json($lotes);

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function Guardar(Request $request)
    {
      try {

        DB::beginTransaction();

        // SalidasResguardo
        // Partidas
        //3 para salidas a resguardo en tipo
        $id = Auth::user();

        $salida = \App\SalidasResguardo::where('proyecto_id',$request->proyecto)
        ->where('fecha',$request->fecha)->where('empleado_solicita_id',$request->empleado)->first();
        if (isset($salida) == false) {
          // code...
        $salida = new \App\SalidasResguardo();
        $salida->fecha = $request->fecha;
        $salida->folio = $this->generarFolio($request->proyecto);
        $salida->proyecto_id = $request->proyecto;
        $salida->tiposalida_id = 3;
        $salida->empleado_entrega_id = $id->empleado_id;
        $salida->empleado_solicita_id = $request->empleado;
        $salida->supervisores_proyectos_id = $this->getSupervisor($request->proyecto_id);
        $salida->save();
        }

        // return response()->json($request->data);

        foreach ($request->data as $key => $value) {
        ////////////////////////////
        /*** Movimientos ***/
        $partidas = \App\Partidas::where('lote_id',$value['id'])->where('tiposalida_id','3')
        ->where('salida_id',$salida->id)->first();

        if (isset($partidas) == true) {
        $partidas->cantidad = $partidas->cantidad + $value['cantidad'];
        $partidas->update();
        }else {
        $partidas = new \App\Partidas();
        $partidas->salida_id = $salida->id;
        $partidas->tiposalida_id = 3;
        $partidas->lote_id = $value['id'];
        $partidas->cantidad = $value['cantidad'];
        $partidas->save();
        }

        $tipo_salida_nombre = "Resg";

        $lote_almacen = \App\LoteAlmacen::where('id', '=', $partidas->lote_id)->first();
        $cantidadresta = $lote_almacen->cantidad - $value['cantidad'];
        $lote_almacen->cantidad = $cantidadresta;
        Utilidades::auditar($lote_almacen, $lote_almacen->id, 2);
        $lote_almacen->update();

        $stokearticulo = \App\StockArticulo::where('articulo_id', '=', $lote_almacen->articulo_id)
            ->where('stocke_id', '=', $lote_almacen->stocke_id)->first();
        $cantidadrestastoke = $stokearticulo->cantidad - $value['cantidad'];
        $stokearticulo->cantidad = $cantidadrestastoke;
        Utilidades::auditar($stokearticulo, $stokearticulo->id, 2);
        $stokearticulo->update();

        $existencias = \App\Existencia::where('id_lote', '=', $lote_almacen->lote_id)
            ->where('articulo_id', '=', $lote_almacen->articulo_id)->first();
        if ($existencias == null)
        {
            return response()->json(new Response(500, "Artículo sin existencias en el lote indicado", 0, false));
        }
        $cantidadrestaexistencia = $existencias->cantidad - $value['cantidad'];
        $existencias->cantidad = $cantidadrestaexistencia;
        Utilidades::auditar($existencias, $existencias->id, 2);
        $existencias->update();

        $stok_request = DB::table('stocks')->where('proyecto_id', $request->proyecto)->first();
        $stocks = \App\Stock::where('id', '=', $stokearticulo->stocke_id)->first();
        $stk_id = 0;
        if ($stok_request->id != $stocks->id)
        {
            $stk_id = $stok_request->id;
            $proyectos = \App\Proyecto::where('id', '=', $stok_request->proyecto_id)->first();
        }
        else
        {
            $stk_id = $stocks->id;
            $proyectos = \App\Proyecto::where('id', '=', $stocks->proyecto_id)->first();
        }

        // Movimiento
        $hoy = date("Y-m-d");
        $hora = date("H:i:s");
        $movimiento = new \App\Movimiento();
        $movimiento->cantidad = $value['cantidad'];
        $movimiento->fecha = $hoy;
        $movimiento->hora = $hora;
        $movimiento->tipo_movimiento = 'Salida ';
        $movimiento->folio = 'Salida-' . $lote_almacen->articulo_id . ' a ' . $tipo_salida_nombre;
        $movimiento->proyecto_id = $proyectos->id;
        $movimiento->lote_id =  $lote_almacen->id;
        $movimiento->stocke_id =  $stk_id;
        $movimiento->almacene_id = $lote_almacen->almacene_id;
        $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null : $lote_almacen->stand_id;
        $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null : $lote_almacen->nivel_id;
        $movimiento->articulo_id = $lote_almacen->articulo_id;
        Utilidades::auditar($movimiento, $movimiento->id, 2);
        $movimiento->save();
      }
        /////////////////////////////////

        DB::commit();
        return response()->json(['status' => true]);
      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }

    public function getSupervisor($id)
    {
      $supervisor = DB::table('supervisores_proyectos')
      ->where('proyecto_id',$id)
      ->where('activo','1')
      ->first();

      return isset($supervisor) == false ? 0 : $supervisor->id;
    }

    public function generarFolio($id)
    {
      // str_pad($folio->consecutivo, 3, "0", STR_PAD_LEFT)
      $d = DB::table('salidasresguardo')->where('proyecto_id',$id)->get();
      $folio = str_pad((count($d) + 1), 4, "0", STR_PAD_LEFT);

      return $folio;
    }

    public function getEncabezados()
    {
      try {
        $data = DB::table('salidasresguardo AS sr')
        ->join('proyectos AS p','p.id','sr.proyecto_id')
        ->join('empleados AS e','e.id','sr.empleado_solicita_id')
        ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),'e.id')
        ->groupBy('e.id')
        ->get();

        return response()->json($data);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function getPartidas($id)
    {
      try {

        $partida = DB::table('partidas AS p')
        ->join('salidasresguardo AS sr','sr.id','p.salida_id')
        ->join('proyectos AS pr','pr.id','sr.proyecto_id')
        ->join('lote_almacen AS la','la.id','p.lote_id')
        ->join('articulos AS a','la.articulo_id','a.id')
        ->select('p.*','a.descripcion','sr.fecha','pr.nombre_corto')
        ->where('p.tiposalida_id','3')
        ->where('sr.empleado_solicita_id',$id)
        // ->where('p.salida_id',$id)
        ->get();
        return response()->json($partida);
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function EliminarP($id)
    {
      try {

        $hoy = date("Y-m-d");
        $hora = date("H:i:s");
        $movimiento = new \App\Movimiento();
        $movimiento->cantidad = $value['cantidad'];
        $movimiento->fecha = $hoy;
        $movimiento->hora = $hora;
        $movimiento->tipo_movimiento = 'Salida ';
        $movimiento->folio = 'Salida-' . $lote_almacen->articulo_id . ' a ' . $tipo_salida_nombre;
        $movimiento->proyecto_id = $proyectos->id;
        $movimiento->lote_id =  $lote_almacen->id;
        $movimiento->stocke_id =  $stk_id;
        $movimiento->almacene_id = $lote_almacen->almacene_id;
        $movimiento->stand_id = ($lote_almacen->stand_id == 'null') ? null : $lote_almacen->stand_id;
        $movimiento->nivel_id = ($lote_almacen->nivel_id == 'null') ? null : $lote_almacen->nivel_id;
        $movimiento->articulo_id = $lote_almacen->articulo_id;
        Utilidades::auditar($movimiento, $movimiento->id, 2);
        $movimiento->save();

      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function RetornoResguardo(Request $request)
    {
      try {
        DB::beginTransaction();

        $partidas_b = DB::table('partidas AS p')
        ->join('lote_almacen AS la','la.id','p.lote_id')
        ->join('stocks AS s','s.id','la.stocke_id')
        ->select('la.*','s.proyecto_id','p.salida_id AS salidaId','p.tiposalida_id AS tipoSalida')
        ->where('p.id',$request->id)->first();

        // echo $request->articulo;
        $validos=$request->cantidad-$request->cantidad_defectuoso;
        $aux_articulo=$partidas_b->articulo_id;

        $fecha = date('Y-m-d');
        $SalidaRetorno = \App\SalidaRetorno::
          where('proyectoId',$partidas_b->proyecto_id)
          ->where('fecha',$fecha)->first();

        if (isset($SalidaRetorno) == false) {
          $SalidaRetorno = new \App\SalidaRetorno();
          $SalidaRetorno->fecha = $fecha;
          $SalidaRetorno->comentarios = 'Retorno Resguardo';
          $SalidaRetorno->tipo_retorno = 3;
          $SalidaRetorno->estado = 1;
          $SalidaRetorno->proyectoId = $partidas_b->proyecto_id;
          Utilidades::auditar($SalidaRetorno, $SalidaRetorno->id);
          $SalidaRetorno->save();
        }
        //Guardar partida
        $partidaRetorno=new \App\PartidasRetorno();
        $partidaRetorno->articulo_id = $partidas_b->articulo_id;
        $partidaRetorno->cantidad_entrada = $request->cantidad;
        $partidaRetorno->cantidad_defectuoso = $request->cantidad_defectuoso;
        $partidaRetorno->proyecto_id = $partidas_b->proyecto_id;
        $partidaRetorno->salida_id = $partidas_b->salidaId;
        $partidaRetorno->tipo_salida = $partidas_b->tipoSalida;
        $partidaRetorno->salida_retorno_id = $SalidaRetorno->id;
        $partidaRetorno->partida_id = $request->id;
        $partidaRetorno->save();
        // Aumentar en almacen
        // Actualizar cantidad en partida
        $partidaActualizar= \App\Partidas::findOrFail($request->id);
        $partidaActualizar->cantidad_retorno+=$request->cantidad;
        Utilidades::auditar($partidaActualizar, $partidaActualizar->id);
        $partidaActualizar->save();

        // Verificar cantidad entrada con salida

        // if($partidaActualizar->cantidad_retorno>$partidaActualizar->cantidad)
        // {
        //     DB::rollback();
        //     return response()->json(["status"=>false,"error"=>"Cantidad de retorno excedida"]);
        //     // throw new Exception("Cantidad de retorono excedida");
        // }

        // Actualizar lote_almacen
        $loteAlmacen= \App\LoteAlmacen::findOrFail($partidas_b->id);
        $loteAlmacen->cantidad+=$validos;
        Utilidades::auditar($loteAlmacen, $loteAlmacen->id);
        $loteAlmacen->save();

        // existencias
        $existencias= \App\Existencia::where('id_lote',$partidas_b->lote_id)->first();
        $existencias->cantidad+=$request->cantidad;
        Utilidades::auditar($existencias, $existencias->id);
        $existencias->save();

        // Movimientos

        $movimiento=new \App\Movimiento();
        $movimiento->cantidad=$request->cantidad;
        $movimiento->fecha=date("Y-m-d");
        $movimiento->hora=date("h:i:s");
        $movimiento->tipo_movimiento= "Retorno";
        $movimiento->folio="RT-0".$request->id."-".$partidas_b->articulo_id;
        $movimiento->proyecto_id= $partidas_b->proyecto_id;
        $movimiento->lote_id=$partidas_b->id;
        $movimiento->stocke_id = $partidas_b->stocke_id;
        $movimiento->almacene_id=$partidas_b->almacene_id;
        $movimiento->stand_id=$partidas_b->stand_id;
        $movimiento->nivel_id=$partidas_b->nivel_id;
        $movimiento->articulo_id=$partidaRetorno->articulo_id;
        Utilidades::auditar($movimiento, $movimiento->id);
        $movimiento->save();

        //Stock articulos
        $stock= \App\StockArticulo::
        where("stocke_id","=",$partidas_b->stocke_id)
        ->where("articulo_id","=",$partidas_b->articulo_id)
        ->firstOrFail();

        $stock->cantidad+=$validos;
        Utilidades::auditar($stock, $stock->id);
        $stock->save();
        DB::commit();

        //Actualizar cantidad en almacen
        return response()->json(["status"      => true]);

      } catch (\Throwable $e) {
        DB::rollBack();
        Utilidades::errors($e);
      }
    }


    public function Descargar($id)
    {
      $empleado_id = $id;
      $arreglo = [];
      $empleado = DB::table('empleados AS e')
      ->join('puestos AS p','p.id','e.puesto_id')
      ->select('e.*','p.nombre AS puesto','p.area')
      ->where('e.id',$id)
      ->first();

      $salida_resguardo = DB::table('partidas AS p')
      ->join('lote_almacen AS la','la.id','p.lote_id')
      ->join('articulos AS a','a.id','la.articulo_id')
      ->join('salidasresguardo AS sr','sr.id','p.salida_id')
      ->join('proyectos AS pr','pr.id','sr.proyecto_id')
      ->where('p.tiposalida_id','3')
      ->where('sr.empleado_solicita_id',$empleado_id)
      ->select('p.*','sr.fecha','sr.proyecto_id','pr.nombre_corto','a.descripcion','a.unidad')
      ->get();


      foreach ($salida_resguardo as $key => $value) {
        $retorno = DB::table('partidas_retorno AS pr')
        ->leftjoin('salidas_retorno AS sr','sr.id','pr.salida_retorno_id')
        ->where('pr.partida_id',$value->id)
        ->select('pr.*','sr.fecha')
        ->first();

        $arreglo [] = [
          'salida' => $value,
          'retorno' => $retorno,
        ];
      }

      // return response()->json($arreglo);

      // $data = [];
    $pdf = \PDF::loadView('pdf.resguardo', compact('arreglo','empleado'));
    $pdf->getDomPDF()->set_option("enable_php", true);

      $pdf->setPaper("letter", "portrait");
      return $pdf->stream();
    }
}
