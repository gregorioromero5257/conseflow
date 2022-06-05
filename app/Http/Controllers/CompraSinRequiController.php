<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compras;
use App\Requisicion;
use App\PartidaRe;
use App\Requisicionhasordencompras;
use App\Adicionales;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosServicios;


class CompraSinRequiController extends Controller
{
  /**
  * 1.- Crear la requisicion con el folio de la orden de compra
  * 2.- Crear las partidas de requisicion filtrando por el folio
  * 3.- Establecer una bandera para no ser vista en las requisiciones
  * 4.- Crear a la par el llenado de las partidas de las requisiciones
  * 5.- Revisar la creacion del pda
  */
  /**
  * Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index()
  {
    //
  }

  /**
  * Show the form for creating a new resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function create()
  {
    //
  }

  /**
  * Store a newly created resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    try {

      if (!$request->ajax()) return redirect('/');
      //crear la requisicion
      $this->GuardarRequisicion($request);
      //cambiar la condicion de la requisicion
      //$this->condicion($requisicione_id,$request->pda,$request->cantidad);

      //guardar la partida de la requisicion
      //$this->GuardarRHC($request);

      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }


  }

  public function GuardarRequisicion($request){
    try {

      $ordencompra = Compras::where('id','=',$request->orden_compra_id)->first();


      if ($request->asme == 0) {
        $requi = Requisicion::where('descripcion_uso','=','ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$ordencompra->folio)
        ->where('estado_id','=','11')->first();

      }elseif ($request->asme == 1) {
        $requi = Requisicion::where('descripcion_uso','=','ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$ordencompra->folio)
        ->where('estado_id','=','5')->first();
      }

      if (isset($requi) == false) {

        if ($request->asme == 0) {
          $req = new Requisicion();
          $req->folio = $ordencompra->folio.'-SR';
          $req->area_solicita_id = 1;
          $req->formato_requisiciones = '---';
          $req->fecha_solicitud = $ordencompra->fecha_orden;
          $req->descripcion_uso = 'ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$ordencompra->folio;
          $req->tipo_compra = 1;
          $req->proyecto_id = $ordencompra->proyecto_id;
          $req->estado_id = 11;//sin requisicion
          $req->condicion = 2;
          $req->solicita_empleado_id = $ordencompra->elabora_empleado_id;//cambiar por compras
          $req->autoriza_empleado_id = $ordencompra->autoriza_empleado_id;
          $req->valida_empleado_id = $ordencompra->elabora_empleado_id;
          $req->asme = $request->asme;
          Utilidades::auditar($req, $req->id);
          $req->save();
        }elseif ($request->asme == 1) {
          $req = new Requisicion();
          $req->folio = $ordencompra->folio.'-ASME';
          $req->area_solicita_id = 1;
          $req->formato_requisiciones = '---';
          $req->fecha_solicitud = $ordencompra->fecha_orden;
          $req->descripcion_uso = 'ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$ordencompra->folio;
          $req->tipo_compra = 1;
          $req->proyecto_id = $ordencompra->proyecto_id;
          $req->estado_id = 5;//sin requisicion
          $req->condicion = 2;
          $req->solicita_empleado_id = $request->elabora_empleado_requisicion_id['id'];//cambiar por compras
          $req->autoriza_empleado_id = $ordencompra->autoriza_empleado_id;
          $req->valida_empleado_id = $ordencompra->elabora_empleado_id;
          $req->asme = $request->asme;
          Utilidades::auditar($req, $req->id);
          $req->save();
        }


        $result =  $this->llenarPartidaRe($req, $request, $ordencompra);
      }
      else {
        $result =  $this->llenarPartidaRe($requi, $request, $ordencompra);
      }
      $this->compraEC($result,$request);
      return true;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function llenarPartidaRe($req, $request, $ordencompra){
    try{

    if ($request->vehiculo_id == 0 && $request->mantenimiento_v_id == 0) {
      $valor_ = null;
    }elseif ($request->vehiculo_id == 0 && $request->mantenimiento_v_id != 0) {
      $valor_ = $request->mantenimiento_v_id;
    }elseif ($request->vehiculo_id != 0 && $request->mantenimiento_v_id == 0) {
      $valor_ = $request->vehiculo_id;
    }
    $partidaRe = new PartidaRe();
    $partidaRe->requisicione_id = $req->id;
    $partidaRe->articulo_id =  $request->articulo_id == 0 ? null : $request->articulo_id;
    $partidaRe->servicio_id = $request->servicio_id == 0 ? null : $request->servicio_id;
    $partidaRe->vehiculo_id = $valor_;
    $partidaRe->peso = 0;
    $partidaRe->cantidad = $request->cantidad;
    $partidaRe->equivalente = 0;
    $partidaRe->fecha_requerido = $req->fecha_solicitud;
    $partidaRe->comentario = $request->comentario;
    $partidaRe->cantidad_compra = $request->cantidad;
    $partidaRe->cantidad_almacen = 0;
    $partidaRe->pda = $pda = Utilidades::crearPda($req->id);
    $partidaRe->condicion = 11;
    $partidaRe->item = $request->item;
    $partidaRe->especificacion = $request->especificacion;
    Utilidades::auditar($partidaRe, $partidaRe->pda);
    $partidaRe->save();



    return $partidaRe;
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }


  public function compraEC($result,$request)
  {
    try{
    if ($request->vehiculo_id == 0 && $request->mantenimiento_v_id == 0) {
      $valor_ = null;
    }elseif ($request->vehiculo_id == 0 && $request->mantenimiento_v_id != 0) {
      $valor_ = $request->mantenimiento_v_id;
    }elseif ($request->vehiculo_id != 0 && $request->mantenimiento_v_id == 0) {
      $valor_ = $request->vehiculo_id;
    }
    $requi = new Requisicionhasordencompras();
    $requi->requisicione_id = $result->requisicione_id;
    $requi->orden_compra_id = $request->orden_compra_id;
    $requi->articulo_id = $result->articulo_id == 0 ? null : $result->articulo_id;
    $requi->servicio_id = $result->servicio_id == 0 ? null : $result->servicio_id;
    $requi->vehiculo_id = $valor_;
    // $requi->vehiculo_id = $request->vehiculo_id == 0 ? null : $request->vehiculo_id;
    $requi->cantidad = $request->cantidad;
    $requi->precio_unitario = $request->precio_unitario;
    $requi->comentario = $request->comentario;
    $requi->cantidad_entrada = $request->cantidad;
    $requi->item = $request->item;
    $requi->especificacion = $request->especificacion;
    Utilidades::auditar($requi, $requi->pda);
    $requi->save();

    if ($request->adicionales > 0) {
      $adicionales = new Adicionales();
      $adicionales->req_has_comp = $requi->id;
      $adicionales->cantidad = $request->adicionales;
      Utilidades::auditar($adicionales, $adicionales->id);
      $adicionales->save();
    }
    // $this->condicion($result->requisicione_id,$result->pda,$request->cantidad);
    if($request->centro_costo_id != ''){
      $costo_partida_compra = new CostosProyectosServicios();
      $costo_partida_compra->catalogo_centro_costos_id = $request->centro_costo_id;
      $costo_partida_compra->requisicion_has_ordencompra_id = $requi->id;
      $costo_partida_compra->save();
    }
    return true;
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
    // code...
  }


  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function eliminar($id)
  {
    //
    $valores = explode("&",$id);
    if ($valores[1] != null) {
      $partidas = \App\PartidaRe::where('articulo_id','=',$valores[0])
      ->where('requisicione_id','=',$valores[1])
      ->delete();
      // ->first();
    }
    if($valores[2] != null) {
      $partidas = \App\PartidaRe::where('servicio_id','=',$valores[2])
      ->where('requisicione_id','=',$valores[1])
      ->delete();
      // ->first();
    }
    if($valores[3] != null) {
      $partidas = \App\PartidaRe::where('vehiculo_id','=',$valores[3])
      ->where('requisicione_id','=',$valores[1])
      ->delete();
      // ->first();
    }

    return response()->json(array('status' => true));
  }


  /**
  * Display the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    //
  }

  /**
  * Show the form for editing the specified resource.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function edit($id)
  {
    //
  }

  /**
  * Update the specified resource in storage.
  *
  * @param  \Illuminate\Http\Request  $request
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    //
  }

  /**
  * Remove the specified resource from storage.
  *
  * @param  int  $id
  * @return \Illuminate\Http\Response
  */
  public function destroy($id)
  {
    //
  }
}
