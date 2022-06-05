<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Requisicionhasordencompras;
use App\Requisicion;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosServicios;
use App\Http\Controllers\CompraSinRequiController;



class RequisicionComprasController extends Controller
{

  public function index()
  {
    if (!$request->ajax()) return redirect('/');

    $requicompra = DB::table('requisicion_has_ordencompras')
    //Datos pertenecientes a requisiicones has compras
    ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
    //Fin
    ->select('requisicion_has_ordencompras.*',
    'req.id AS rid',
    'req.folio AS rf',
    'req.fecha_solicitud AS rfs',
    'p.nombre AS proyecton',
    'a.id AS aid',
    'a.descripcion AS ad'
    )
    ->get();


    return response()->json($requicompra);
  }


  public function store(Request $request)
  {
    try {
      if ($request->tipo == 1) {
        $object = new CompraSinRequiController;
        $object->GuardarRequisicion($request);
        return response()->json(array('status' => true));

      }else {
        if (!$request->ajax()) return redirect('/');
        if($request->cambio_equivalente == 1){
          $this->comprarequivalente($request);
          $this->condicion($request->requisicione_id,$request->pda,$request->cantidad);
          return response()->json(array('status' => true));
        }
        else {
          $requisicione_id = $request->requisicione_id;
          $articulo_id = $request->articulo_id;
          $servicio_id = $request->servicio_id;

          $proyecto = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*','r.proyecto_id AS proyecto_id')
          ->leftJoin('requisiciones AS r','r.id','=','requisicion_has_ordencompras.requisicione_id')
          ->leftJoin('proyectos AS p','p.id','=','r.proyecto_id')
          ->where('p.id','=',$request->proyecto_id)
          ->where('requisicion_has_ordencompras.orden_compra_id','=',$request->orden_compra_id)->get();
          $ordencompra = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*')
          ->where('requisicion_has_ordencompras.orden_compra_id','=',$request->orden_compra_id)->get();

          if (count($ordencompra) == 0 && count($proyecto) == 0 ) {
            $this->condicion($requisicione_id,$request->pda,$request->cantidad);
            $this->GuardarRHC($request);

            return response()->json(array('status' => true));
          }
          elseif (count($ordencompra) == 0 || count($proyecto) == 0) {
            return response()->json(array('status' => false));
          }
          elseif ($proyecto[0]->proyecto_id == $request->proyecto_id ) {
            $this->condicion($requisicione_id,$request->pda,$request->cantidad);
            $this->GuardarRHC($request);

            return response()->json(array('status' => true));
          }
        }
      }
    }  catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }



  }

  public function apartadosGuardar(Request $request)
  {
    try {
      // return response()->json($request);
      $requisicione_id = $request->requisicione_id;
      $articulo_id = $request->articulo_id;
      $servicio_id = $request->servicio_id;

      $proyecto = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*','r.proyecto_id AS proyecto_id')
      ->leftJoin('requisiciones AS r','r.id','=','requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('proyectos AS p','p.id','=','r.proyecto_id')
      ->where('p.id','=',$request->proyecto_id)
      ->where('requisicion_has_ordencompras.orden_compra_id','=',$request->orden_compra_id)->get();
      $ordencompra = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*')
      ->where('requisicion_has_ordencompras.orden_compra_id','=',$request->orden_compra_id)->get();

      if (count($ordencompra) == 0 && count($proyecto) == 0 ) {
        // $this->condicionApa($requisicione_id,$request->pda,$request->cantidad);
        DB::table('partidas_requisiciones')->where([['requisicione_id', '=',  $requisicione_id],['pda','=',$request->pda],])
        ->update(['apartado_comprado' => 1]);
        $this->GuardarRHC($request);

        return response()->json(array('status' => true));
      }
      elseif (count($ordencompra) == 0 || count($proyecto) == 0) {
        return response()->json(array('status' => false));
      }
      elseif ($proyecto[0]->proyecto_id == $request->proyecto_id ) {
        // $this->condicionApa($requisicione_id,$request->pda,$request->cantidad);
        DB::table('partidas_requisiciones')->where([['requisicione_id', '=',  $requisicione_id],['pda','=',$request->pda],])
        ->update(['apartado_comprado' => 1]);
        $this->GuardarRHC($request);

        return response()->json(array('status' => true));
      }

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function GuardarRHC($request)
  {
    $requi = new Requisicionhasordencompras();
    //$requi->fill($request->all());
    $requi->requisicione_id = $request->requisicione_id;
    $requi->orden_compra_id = $request->orden_compra_id;
    $requi->articulo_id = $request->articulo_id;
    $requi->servicio_id = $request->servicio_id;
    $requi->cantidad = $request->cantidad;
    $requi->precio_unitario = $request->precio_unitario;
    $requi->comentario = $request->comentario;
    $requi->cantidad_entrada = $request->cantidad;
    Utilidades::auditar($requi, $requi->id);
    $requi->save();

    if($request->centro_costo_id != ''){
      $costo_partida_compra = new CostosProyectosServicios();
      $costo_partida_compra->catalogo_centro_costos_id = $request->centro_costo_id;
      $costo_partida_compra->requisicion_has_ordencompra_id = $requi->id;
      $costo_partida_compra->save();
    }

    if ($request->adicionales > 0) {
      $adicionales = new \App\Adicionales();
      $adicionales->req_has_comp = $requi->id;
      $adicionales->cantidad = $request->adicionales;
      Utilidades::auditar($adicionales, $adicionales->id);
      $adicionales->save();
    }

    return true;
  }

  public function condicion($requisicione_id,$pda,$cantidad){
    $requisis = \App\PartidaRe::where([['requisicione_id', '=', $requisicione_id],['pda','=',$pda],])->firstOrFail();

    if ($cantidad >= $requisis->cantidad_compra) {
      if ($requisis->condicion==0) {
        DB::table('partidas_requisiciones')->where([['requisicione_id', '=',  $requisicione_id],['pda','=',$pda],])
        ->update(['condicion' => 1],['cantidad_compra' => $cantidad]);
      }else{
        DB::table('partidas_requisiciones')->where([['requisicione_id', '=', $requisicione_id],['pda','=',$pda],])
        ->update(['condicion' => 0],['cantidad_compra' => $cantidad]);
      }

    }
    elseif ($cantidad < $requisis->cantidad_compra) {
      $cantidad_compra_nueva = $requisis->cantidad_compra - $cantidad;
      DB::table('partidas_requisiciones')->where([['requisicione_id','=',$requisicione_id],['pda','=',$pda],])
      ->update(['cantidad_compra' => $cantidad_compra_nueva]);
    }


    return true;
  }

  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $requi = Requisicion::findOrFail($request->id);
    $requi->fill($request->all());
    Utilidades::auditar($requi, $requi->id);
    $requi->save();

    return response()->json(array('status' => true));

  }

  public function show(Request $request)
  {
    $estados = 0;
    $empleados = Requisicion::orderBy('id', 'asc')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('tipo_compra AS tc', 'tc.id', '=', 'requisiciones.tipo_compra_id')
    ->select('requisiciones.*','p.nombre AS nombrep','tc.nombre AS nombretc')
    ->get();
    foreach ($empleados as $key => $empleado) {
      $arreglo[] = [
        'empleado' => $empleado,
      ];
    }
    return response()->json($arreglo);

  }

  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $requi = Requisicion::findOrFail($request->id);
    $requi->condicion = '1';
    Utilidades::auditar($requi, $requi->id);
    $requi->save();
  }

  public function edit($id){

    $requi = Requisicionhasordencompras::where('id','=',$id)->delete();
    return response()->json(array('status' => true));
  }

  /**
  * [comprarequivalente Creamos el encabezado de una nueva requisicion con comentario de "por compra de equivalente de la
  * orden de compra folio", llenamos las partidas de la nueva requisicion ]
  * @param  Request $request [description]
  * @return [type]           [description]
  */
  public function comprarequivalente(Request $request)
  {
    $requisicion = Requisicion::where('id','=',$request->requisicione_id)->first();
    $partidaRe = \App\PartidaRe::where('requisicione_id','=',$request->requisicione_id)
    ->where('pda','=',$request->pda)->first();
    $requi = Requisicion::where('descripcion_uso','=','POR EQUIVALENTE DE LA COMPRA: '.$request->folio)
    ->where('estado_id','=','9')->first();
    if ($requi == null) {
      $req = new Requisicion();
      $req->folio = $requisicion->folio.'-C';
      $req->area_solicita_id = $requisicion->area_solicita_id;
      $req->formato_requisiciones =$requisicion->formato_requisiciones;
      $req->fecha_solicitud = $requisicion->fecha_solicitud;
      $req->descripcion_uso = 'POR EQUIVALENTE DE LA COMPRA: '.$request->folio;
      $req->tipo_compra = $requisicion->tipo_compra;
      $req->proyecto_id = $requisicion->proyecto_id;
      $req->estado_id = 9;//cambio por equivalente
      $req->condicion = 2;
      $req->solicita_empleado_id = $requisicion->solicita_empleado_id;//cambiar por compras
      $req->autoriza_empleado_id = $requisicion->autoriza_empleado_id;
      $req->valida_empleado_id = $requisicion->valida_empleado_id;
      Utilidades::auditar($req, $req->id);
      $req->save();
    $result =  $this->llenarPartidaRe($req, $partidaRe, $request);
    }
    else {
    $result =  $this->llenarPartidaRe($requi, $partidaRe, $request);
    }
     $this->compraEC($result,$request);
    // $partidaReq = \App\PartidaRe::where('requisicione_id','=',$request->requisicione_id)
    // ->where('pda','=',$request->pda)->update(['condicion' => 3]);

    // return response()->json(array('status' => true));
    return response()->json($partidaRe);
  }

  public function compraEC($result,$request)
  {
    $requi = new Requisicionhasordencompras();
    $requi->requisicione_id = $result->requisicione_id;
    $requi->orden_compra_id = $request->orden_compra_id;
    $requi->articulo_id = $result->articulo_id;
    $requi->servicio_id = $result->servicio_id;
    $requi->cantidad = $request->cantidad;
    $requi->precio_unitario = $request->precio_unitario;
    $requi->cantidad_entrada = $request->cantidad;
    Utilidades::auditar($requi, $requi->id);
    $requi->save();

    if ($request->adicionales > 0) {
      $adicionales = new \App\Adicionales();
      $adicionales->req_has_comp = $requi->id;
      $adicionales->cantidad = $request->adicionales;
      Utilidades::auditar($adicionales, $adicionales->id);
      $adicionales->save();
    }
    // $this->condicion($result->requisicione_id,$result->pda,$request->cantidad);

    return true;
    // code...
  }

  /**
  * [llenarPartidaRe funcion que llena las partidas de las compras cambiadas  ]
  * @param  Object $req [description]
  * @param  Object $partida
  * @param  Object $request
  * @return [type]           [description]
  */
  public function llenarPartidaRe($req, $partida, $request)
  {
    $partidaRe = new \App\PartidaRe();
    $partidaRe->requisicione_id = $req->id;
    $partidaRe->articulo_id = $request->articulo_id;
    $partidaRe->servicio_id = $request->servicio_id;
    $partidaRe->peso = $partida->peso;
    $partidaRe->cantidad = $request->cantidad;
    $partidaRe->equivalente = 0;
    $partidaRe->fecha_requerido = $partida->fecha_requerido;
    $partidaRe->comentario = "Por equivalente/cambio de: -".$request->articulo_uno_id.'-'.$request->nombrearti_uno;
    $partidaRe->cantidad_compra = $request->cantidad;
    $partidaRe->cantidad_almacen = 0;
    $partidaRe->pda = $pda = Utilidades::crearPda($req->id);
    $partidaRe->condicion = 3;
    Utilidades::auditar($partidaRe, $partidaRe->pda);
    $partidaRe->save();

    // $lote_temporal = \App\LoteTemporal::where('requisicion_id','=',$request->requisicione_id)
    // ->where('articulo_id','=',$request->articulo_uno_id)->first();
    // if ($lote_temporal != null) {
    //   $lote_temporal->requisicion_id = $partidaRe->requisicione_id;
    //   $lote_temporal->articulo_id = $request->articulo_id;
    //   $lote_temporal->save();
    // }

    return $partidaRe;
  }

  public function articulosconsulta()
  {
    $com = Requisicionhasordencompras::leftJoin('ordenes_compras AS OC','OC.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('articulos AS A','A.id','=','requisicion_has_ordencompras.articulo_id')
    ->select('A.*','OC.folio')->where('requisicion_has_ordencompras.articulo_id','!=','null')->get();

    return response()->json($com);
  }

  /**
  * Funcion que actualiza las partidas de las ordenes de compras sin requisicion
  */
  public function actualizarpartidacompra(Request $request)
  {
    try {
      $rhoc = Requisicionhasordencompras::where('id',$request->id_partida)->first();
      $rhoc->precio_unitario = $request->precio_unitario;
      $rhoc->cantidad = $request->cantidad;
      $rhoc->comentario = $request->comentario;
      $rhoc->save();

      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }


}
