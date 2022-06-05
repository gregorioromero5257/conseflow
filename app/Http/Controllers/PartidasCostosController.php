<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartidasCostos;
use App\PartidasCostosNodos;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;


class PartidasCostosController extends Controller
{


  protected $rules = array(
   /* 'concepto' => 'required',*/
  );

  /**
  * [Funcion que obtiene todos los registros del modelo PartidasCostos apartir de un
  * $id recibido]
  * @param Request $request [Url del get GET]
  * @param Int     $id      [id del proyecto]
  */
  public function index(Request $request, $id)
  {
    $facturas_entradas = \App\FacturasEntradas::leftJoin('partidas_costos AS PC','PC.id','=','facturas_entradas.partidas_costos_id')
    ->join('proyectos AS P','P.id','=','PC.proyecto_id')
    ->select('facturas_entradas.*')->where('PC.proyecto_id','=',$id)->get();

    foreach ($facturas_entradas as $key => $value) {
      $partidasCostos_uno = \App\PartidasCostos::where('id',$value->partidas_costos_id)->first();
      $partidasCostos_uno->monto_ejecutado = 0;
      $partidasCostos_uno->save();

    }

    foreach ($facturas_entradas as $key => $value) {
      $partidasCostos_uno = \App\PartidasCostos::where('id',$value->partidas_costos_id)->first();
      $partidasCostos_uno->monto_ejecutado += $value->total;
      $partidasCostos_uno->save();

    }

          $partidasCostos = PartidasCostos::select('partidas_costos.*', 'tipo_partida.nombre AS tipo_partida',DB::raw('(partidas_costos.monto_cotizado - partidas_costos.monto_ejecutado) AS diferencia'))
    ->join('tipo_partida', 'tipo_partida_id', '=', 'tipo_partida.id')
    ->where('proyecto_id', $id)->get()->toArray();

    return response()->json($partidasCostos);
  }

  /**
  * [Guarda en BD las partidas en la tabla partidas_costos respetando las condiciones y reglas
  *  establecidas]
  * @param Request   $request [Objeto de datos del POST]
  * @return Response          [Array con estatus true]
  */
  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $partidasCostos = new PartidasCostos();
    $partidasCostos->nombre = $request->nombre;
    $partidasCostos->folio = $request->folio;
    $partidasCostos->monto_cotizado = $request->monto_cotizado;
    $partidasCostos->monto_ejecutado = $request->monto_ejecutado;
    $partidasCostos->tipo_partida_id = $request->tipo_partida_id;
    $partidasCostos->proyecto_id = $request->proyecto_id;
    $partidasCostos->moneda = $request->moneda;
    $partidasCostos->save();

    return response()->json(array(
      'status' => true
    ));
  }

  /**
  * [Actualiza en BD los registros de la tabla partidas_costos apartir de un id proporcionado respetando
  * las reglas definidas]
  * @param  Request  $request [Objeto de datos del PUT]
  * @param  Int      $id      [id del PUT]
  * @return Response          [Array con estatus true]
  */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $partidasCostos = PartidasCostos::findOrFail($request->id);
    $partidasCostos->nombre = $request->nombre;
    $partidasCostos->folio = $request->folio;
    $partidasCostos->monto_cotizado = $request->monto_cotizado;
    $partidasCostos->monto_ejecutado = $request->monto_ejecutado;
    $partidasCostos->tipo_partida_id = $request->tipo_partida_id;
    $partidasCostos->proyecto_id = $request->proyecto_id;
    $partidasCostos->moneda = $request->moneda;
    $partidasCostos->save();

    return response()->json(array(
      'status' => true
    ));
  }

  /**
  * [Funcion que obtiene los registros hijos de la tabla partidas_costos_nodos apartir
  * de un id proporcionado]
  * @param  Int      $id       [id del proyecto]
  * @return response           [Array de tipo JSON]
  */
  public function partidasnodoscostos($id)
  {
    $partidas = \DB::table('partidas_costos_nodos')->select('partidas_costos_nodos.*')
    ->where('ultimo', '!=', '1' )->where('proyecto_id','=',$id)->get();
    return response()->json($partidas);
  }

  /**
  * [Guarda un registro en BD de la tabla partidas_costos_nodos respetando las reglas establecidas]
  * @param  Request  $request [Objeto de datos del POST]
  * @return Response          [Array con estatus true]
  *
  */
  public function insertar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    $partidas = new \App\PartidasCostosNodos();
    $partidas->codigo = Utilidades::codigoPartidaCosto($request);
    $partidas->concepto = $request->concepto;
    $partidas->padre_id = $request->padre_id;
    $partidas->unidad = $request->unidad;
    $partidas->cantidad = $request->cantidad;
    $partidas->p_suministro = $request->p_suministro;
    $partidas->p_instalacion = $request->p_instalacion;
    $partidas->p_unitario = $request->p_unitario;
    $partidas->importe = $request->importe;
    $partidas->proyecto_id = $request->proyecto_id;
    $partidas->ultimo = $request->ultimo;
    $partidas->save();
    return response()->json(array('status' => true));
  }

  /**
  * [Actualiza un registro en BD de la tabla partidas_costos_nodos respetando las reglas establecidas]
  * @param  Request  $request [Objeto de datos del PUT]
  * @return Response          [Array con estatus true]
  */
  public function actualizarnodo(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rules);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }
    $partidas = \App\PartidasCostosNodos::where('id',$request->id)->first();
    // $partidas->codigo = Utilidades::codigoPartidaCosto($request);
    $partidas->concepto = $request->concepto;
    $partidas->padre_id = $request->padre_id;
    $partidas->unidad = $request->unidad;
    $partidas->cantidad = $request->cantidad;
    $partidas->p_suministro = $request->p_suministro;
    $partidas->p_instalacion = $request->p_instalacion;
    $partidas->p_unitario = $request->p_unitario;
    $partidas->importe = $request->importe;
    $partidas->proyecto_id = $request->proyecto_id;
    $partidas->ultimo = $request->ultimo;
    $partidas->save();
    return response()->json(array('status' => true));
  }

  /**
  * [Selecciona todos los registros que ultimo != 0 para se consultados apartir de un proyecto
  * seleccionado]
  * @param  Int    $id       [id del proyecto]
  * @return Object $partidas [Array de objetos en formato JSON]
  */
  public function partidascostosnodos($id)
  {
    $partidas = \DB::table('partidas_costos_nodos')->select('partidas_costos_nodos.*')
    ->where('ultimo','!=','0')->where('proyecto_id','=',$id)->get();

    return response()->json($partidas);
  }



}
