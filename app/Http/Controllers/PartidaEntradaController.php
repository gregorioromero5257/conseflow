<?php

namespace App\Http\Controllers;

use App\Almacene;
use App\PartidaEntrada;
use App\Precios;
use App\LoteAlmacen;
use App\Requisicionhasordencompras;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;
use App\Articulo;
use App\CatalogoCentroCostos;
use App\Categoria;
use App\CentroCostos;
use App\Entrada;
use App\Existencia;
use App\Lote;
use App\Movimiento;
use App\Partidas;
use App\Stock;
use Carbon\Carbon;
use Exception;

class PartidaEntradaController extends Controller
{
  /**
   * [index Consulta de la tabla requisicion_has_ordencompras de la BD]
   * @return Response [devuelve un array transformado en Json]
   */
  public function index()
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = DB::table('requisicion_has_ordencompras')
      ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id', '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('proveedores AS pro', 'pro.id', '=', 'oc.proveedore_id')
      ->leftJoin('requisiciones AS p', 'p.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('proyectos AS pr', 'pr.id', '=', 'p.proyecto_id')
      ->leftJoin('stocks AS S', 'S.proyecto_id', '=', 'pr.id')
      ->select(
        'requisicion_has_ordencompras.*',
        'a.id AS ida',
        'oc.id AS rid',
        'a.descripcion AS descripciona',
        'pro.razon_social AS prazonsocial',
        'pro.id AS idproveedor',
        'p.proyecto_id AS proyecto_id',
        'pr.nombre AS proyectonombre',
        'S.nombre AS stokenombre',
        'S.id AS stokeid',
        'oc.folio AS ocf',
        'oc.id AS ocid',
        'oc.fecha_orden AS ocfo'

      )->where('requisicion_has_ordencompras.condicion', '=', '1')
      ->where('oc.estado_id', '!=', '3')
      ->where('oc.condicion', '!=', '1')
      ->get();

    return response()->json($data);
  }

  public function verordencompra($id)
  {
    $data = DB::table('requisicion_has_ordencompras')
      ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id', '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('proveedores AS pro', 'pro.id', '=', 'oc.proveedore_id')
      ->leftJoin('requisiciones AS p', 'p.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('proyectos AS pr', 'pr.id', '=', 'oc.proyecto_id')
      // ->leftJoin('stocks AS S','S.proyecto_id','=','oc.proyecto_id')
      ->select(
        'requisicion_has_ordencompras.*',
        'a.id AS ida',
        'oc.id AS rid',
        'a.descripcion AS descripciona',
        'pro.razon_social AS prazonsocial',
        'pro.id AS idproveedor',
        'oc.proyecto_id AS proyecto_id',
        'pr.nombre AS proyectonombre',
        // 'S.nombre AS stokenombre',
        // 'S.id AS stokeid',
        'oc.folio AS ocf',
        'oc.id AS ocid',
        'oc.fecha_orden AS ocfo'

      )->where('requisicion_has_ordencompras.condicion', '=', '1')
      // ->where('oc.estado_id','!=','3')
      ->where('oc.condicion', '!=', '1')
      ->where('oc.id', '=', $id)
      ->where('requisicion_has_ordencompras.articulo_id', '!=', 'null')
      ->get();

    $arreglo[] = [
      'datos' => $data,
      'contador' => count($data),
    ];

    return response()->json($arreglo);
  }
  /**
   * [busqueda_filterByColumn Construción del query de consulta]
   * @param  Array $data    [description]
   * @param  Array $queries [description]
   * @return Function          [description]
   */
  protected function busqueda_filterByColumn($data, $queries)
  {
    $queries = json_decode($queries, true);

    return $data->where(function ($q) use ($queries)
    {
      foreach ($queries as $field => $query)
      {
        $_field = $field;

        if (is_string($query))
        {
          $q->where($_field, 'LIKE', "%{$query}%");
        }
        else
        {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  /**
   * [busqueda_filter description]
   * @param  Array $data   [description]
   * @param  Array $query  [description]
   * @param  Array $fields [description]
   * @return Function       [description]
   */
  protected function busqueda_filter($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields)
    {
      foreach ($fields as $index => $field)
      {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
  }

  /**
   * [store Genera un nuevo Registro con los datos para una nueva orden de compra]
   * @param  Request $request [Datos de la orden de compra]
   * @return Response         [status => true]
   */
  public function store(Request $request)
  {
    try
    {
      DB::beginTransaction();
      if (!$request->ajax()) return redirect('/');

      $partida_entrada_busqueda = PartidaEntrada::where('entrada_id', $request->entrada_id)
        ->where('req_com_id', $request->req_com_id)->first();

      if (isset($partida_entrada_busqueda) == true)
      {
        $partida_entrada_busqueda->cantidad = $partida_entrada_busqueda->cantidad +  $request->cantidad;
        Utilidades::auditar($partida_entrada_busqueda, $partida_entrada_busqueda->id);

        $part_entr = DB::table('partidas_entradas')->select(DB::raw('SUM(cantidad) as cantidad'))->where('req_com_id', $request->req_com_id)->first();
        if (isset($part_entr->cantidad) == true)
        {
          $this->actualiza_cantidad($request->req_com_id, $part_entr->cantidad, $request->cantidad);
        }
        else
        {
          $this->guardar_cantidad($request->req_com_id, $request->cantidad);
        }
        $partida_entrada_busqueda->save();
        $this->condicion($request->req_com_id, $request->cantidad);
        $this->lote($request, $partida_entrada_busqueda);
      }
      else
      {

        $partidaentrada = new PartidaEntrada();
        $partidaentrada->entrada_id = $request->entrada_id;
        $partidaentrada->req_com_id = $request->req_com_id;
        $partidaentrada->articulo_id = $request->articulo_id;
        $partidaentrada->cantidad = $request->cantidad;
        $partidaentrada->almacene_id = $request->almacene_id;
        $partidaentrada->nivel_id = $request->nivel_id;
        $partidaentrada->stand_id = $request->stand_id;
        $partidaentrada->validacion_calidad = 0;
        Utilidades::auditar($partidaentrada, $partidaentrada->id);

        $part_entr = DB::table('partidas_entradas')->select(DB::raw('SUM(cantidad) as cantidad'))->where('req_com_id', $request->req_com_id)->first();
        if (isset($part_entr->cantidad) == true)
        {
          $this->actualiza_cantidad($request->req_com_id, $part_entr->cantidad, $request->cantidad);
        }
        else
        {
          $this->guardar_cantidad($request->req_com_id, $request->cantidad);
        }
        $partidaentrada->save();
        $this->condicion($request->req_com_id, $request->cantidad);
        $this->lote($request, $partidaentrada);
      }
      DB::commit();
      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  public function guardar_cantidad($orden_compra_id, $cantidad)
  {
    $requisis = \App\Requisicionhasordencompras::where('id', '=', $orden_compra_id)->first();
    $requisis->cantidad_entrada = (floatval($requisis->cantidad) - floatval($cantidad));
    Utilidades::auditar($requisis, $requisis->id);
    $requisis->save();

    return true;
  }

  public function actualiza_cantidad($orden_compra_id, $cantidad, $cantidad_request)
  {
    $requisis = \App\Requisicionhasordencompras::where('id', '=', $orden_compra_id)->first();
    $requisis->cantidad_entrada = (floatval($requisis->cantidad) - (floatval($cantidad) + floatval($cantidad_request)));
    Utilidades::auditar($requisis, $requisis->id);
    $requisis->save();

    return true;
  }


  /**
   * [condicion Actualiza el campo condicion en la tabla requisicion_has_ordencompras de la BD]
   * @param  Int $orden_compra_id [description]
   * @return Boolean              [description]
   */
  public function condicion($orden_compra_id, $cantidad)
  {
    $requisis = \App\Requisicionhasordencompras::where('id', '=', $orden_compra_id)->first();

    if ($requisis->cantidad_entrada <= 0)
    {
      $requisis->condicion = 0;
      $requisis->save();
    }
    //
    // if ($requisis->condicion==0) {
    //   DB::table('requisicion_has_ordencompras')->where('id', '=',  $orden_compra_id)->update(['condicion' => 1]);
    // }else{
    //   DB::table('requisicion_has_ordencompras')->where('id', '=', $orden_compra_id)->update(['condicion' => 0]);
    // }
    return true;
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

      $partida_entrada = DB::table('partidas_entradas AS pe')
        ->where('id', $id)->first();

      $stock_articulo_id = 0;
      $this->condicion_eliminar($partida_entrada->req_com_id, $partida_entrada->cantidad);

      $lote = \App\Lote::where('entrada_id', $partida_entrada->id)->first();
      $lote->cantidad =  (float)$lote->cantidad -  (float)$partida_entrada->cantidad;
      $lote->save();

      $lote_almacen = \App\LoteAlmacen::where('lote_id', $lote->id)->first();
      $lote_almacen->cantidad =  (float)$lote_almacen->cantidad -  (float)$partida_entrada->cantidad;
      $lote_almacen->save();

      $existencias = \App\Existencia::where('id_lote', $lote->id)->first();
      $existencias->cantidad =  (float)$existencias->cantidad -  (float)$partida_entrada->cantidad;
      Utilidades::auditar($existencias, $existencias->id);
      $existencias->save();

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
   * [calidad Validación de calidad ]
   * @param  Int $id [description]
   * @return Response   [status => true]
   */
  public function calidad(Request $request)
  {
    $ids = Auth::id();
    $user = DB::table('users')->select('users.*', 'e.id AS idemp')->leftJoin('empleados AS e', 'e.id', '=', 'users.empleado_id')->where('users.id', '=', $ids)->first();

    $idusem = $user->idemp;

    $entrada = \App\Entrada::where('id',  '=',  $request->entrada_id)->firstOrFail();
    $entrada->empleado_calidad_id = $idusem;
    $entrada->save();

    $partidaentrda = PartidaEntrada::where([
      ['entrada_id',  '=',  $request->entrada_id],
      ['req_com_id', '=', $request->orden_compra_id], ['articulo_id', '=', $request->articulo_id],
    ])->firstOrFail();

    if ($partidaentrda->validacion_calidad == 1)
    {
      // DB::table('partidas_entradas')
      // ->where([['entrada_id',	'=',	$request->entrada_id],['req_com_id', '=', $request->orden_compra_id],['articulo_id', '=', $request->articulo_id],])
      // ->update(['validacion_calidad' => 0]);
      $lote_nombre = $this->lote($request, $partidaentrda);
      // $this->actualizaLoteTemporal($request->orden_compra_id);
      return response()->json(array('status' => true, 'lote_nombre' => $lote_nombre));
    }
    //return response()->json(array('status' => true,'lote_nombre'=> 'lote 123'));
  }

  /**
   * [actualizaLoteTemporal Agrega en el modelo lote_almacen la cantidad apartada en el lote_temporal]
   * @param  Int $id [description]
   * @return Response     [description]
   */
  // public function actualizaLoteTemporal($id)
  // {
  //   $lote_temporal = \App\LoteTemporal::where('req_com_id','=',$id)->get();
  //   if (count($lote_temporal) != 0) {
  //     for ($i=0; $i < count($lote_temporal) ; $i++) {
  //       $lote_almacen = \App\LoteAlmacen::where('id','=',$lote_temporal[$i]->lote_almacen_id)->first();
  //       $lote_almacen->cantidad = $lote_almacen->cantidad + $lote_temporal[$i]->cantidad;
  //       $lote_almacen->save();
  //       $lote_temporal[$i]->cantidad = 0;
  //       $lote_temporal[$i]->save();
  //     }
  //   }
  //   return true;
  // }

  /**
   * [calidadsalida description]
   * @param  Int $id [description]
   * @return Response   [status => true]
   */
  public function calidadsalida($id)
  {
    $ids = Auth::id();
    $valores = explode("&", $id);
    $user = DB::table('users')->select('users.*', 'e.id AS idemp')->leftJoin('empleados AS e', 'e.id', '=', 'users.empleado_id')->where('users.id', '=', $ids)->first();
    $idusem = $user->idemp;
    // DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombree")
    $entrada = \App\Entrada::where('id',  '=',  $valores[0])->firstOrFail();
    $entrada->empleado_calidad_id = $idusem;
    $entrada->save();
    // return response()->json($user);
    $partidaentrda = PartidaEntrada::where('id', $valores[3])->firstOrFail();
    if ($partidaentrda->validacion_calidad == 1)
    {
      DB::table('partidas_entradas')
        ->where('id', $valores[3])
        ->update(['validacion_calidad' => 2]);
      return response()->json(array('status' => true));
    }
  }

  /**
   * [update description]
   * @param  Request $request [description]
   * @return Response   [status => true]
   */
  public function updatepr(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    //  return response()->json($request);
    $entrada = \App\PartidaEntrada::where('id', $request->id)->first();
    $entrada->fill($request->all());
    // $entrada->factura_id = $request->factura_id;
    $entrada->save();

    return response()->json(array(
      'status' => true
    ));
  }

  /*  * [update description]
  * @param  Request $request [description]
  * @return Response   [status => true]
  */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    //  return response()->json($request);
    $entrada = \App\PartidaEntrada::where('id', $request->id)->first();
    // $entrada->fill($request->all());
    $entrada->factura_id = $request->factura_id;
    $entrada->numero_serie = $request->numero_serie;
    $entrada->precio_unitario = $request->precio_unitario;
    $entrada->save();

    return response()->json(array(
      'status' => true
    ));
  }

  /**
   * [lote Inserción de registro a las tablas 'lotes','lote_almacen','existencias','movimientos' y 'stoke_articulos']
   * @param  String $datos         [description]
   * @param  Array  $partidaentrda [description]
   * @return Boolean               [description]
   */
  public function lote($request, $partidaentrda)
  {

    try
    {

      DB::beginTransaction();
      // return response()->json($request);
      $sp = $this->getStoke($request->req_com_id);

      $lote_busqueda = \App\Lote::where('entrada_id', $partidaentrda->id)
        ->where('articulo_id', $request->articulo_id)->first();

      if (isset($lote_busqueda) == true)
      {
        $lote = \App\Lote::where('entrada_id', $partidaentrda->id)
          ->where('articulo_id', $request->articulo_id)->first();
        $lote->cantidad = $lote->cantidad  + $request->cantidad;
        Utilidades::auditar($lote, $lote->id);
        $lote->save();

        $lote_almacen = \App\LoteAlmacen::where('lote_id', $lote->id)->first();
        $lote_almacen->cantidad =   $lote_almacen->cantidad + $request->cantidad;
        Utilidades::auditar($lote_almacen, $lote_almacen->id);
        $lote_almacen->save();
      }
      else
      {
        $lote = new \App\Lote();
        $lote->nombre = 'lote ' . $request->entrada_id . '-' . $request->articulo_id;
        $lote->entrada_id = $partidaentrda->id;
        $lote->articulo_id = $request->articulo_id;
        $lote->cantidad = $request->cantidad;
        // $lote->caducidad = ($request->caducidad == 'null') ? null:$request->caducidad;
        Utilidades::auditar($lote, $lote->id);
        $lote->save();
        $lote->nombre = 'lote ' . $request->entrada_id . '-' . $request->articulo_id . '-' . $lote->id;
        $lote->save();

        $valores_uno = str_replace('lote ', '', $lote->nombre);
        $valores_dos = explode('-', $valores_uno);

        $lote_almacen = new \App\LoteAlmacen();
        $lote_almacen->lote_id = $lote->id;
        $lote_almacen->almacene_id = ($request->almacene_id == 'null') ? null : $request->almacene_id;
        $lote_almacen->nivel_id = ($request->nivel_id == 'null') ? null : $request->nivel_id;
        $lote_almacen->stand_id = ($request->stand_id == 'null') ? null : $request->stand_id;
        $lote_almacen->cantidad = $request->cantidad;
        // $lote_almacen->caducidad = ($request->caducidad == 'null') ? null:$request->caducidad;
        $lote_almacen->stocke_id = $sp->stocke_id;
        $lote_almacen->articulo_id = $request->articulo_id;
        $lote_almacen->codigo_barras = 'MCF ' . $valores_dos[0] . ' ' . $valores_dos[1] . ' ' . $valores_dos[2];
        Utilidades::auditar($lote_almacen, $lote_almacen->id);
        $lote_almacen->save();
      }



      // $this->guardarPrecio($request, $lote_almacen);

      $existencias = new \App\Existencia();
      $existencias->id_lote = $lote->id;
      $existencias->articulo_id = $request->articulo_id;
      $existencias->almacene_id = ($request->almacene_id == 'null') ? null : $request->almacene_id;
      $existencias->nivel_id = ($request->nivel_id == 'null') ? null : $request->nivel_id;
      $existencias->stand_id = ($request->stand_id == 'null') ? null : $request->stand_id;
      $existencias->cantidad = $request->cantidad;
      Utilidades::auditar($existencias, $existencias->id);
      $existencias->save();

      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $movimiento = new \App\Movimiento();
      $movimiento->cantidad = $request->cantidad;
      $movimiento->fecha = $hoy;
      $movimiento->hora = $hora;
      $movimiento->tipo_movimiento = 'Entrada';
      $movimiento->folio = 'Entrada-' . $sp->proyecto_id . $sp->stocke_id;
      $movimiento->proyecto_id =  $sp->proyecto_id;
      $movimiento->lote_id =  $lote_almacen->id;
      $movimiento->stocke_id =  $sp->stocke_id;
      $movimiento->almacene_id = ($request->almacene_id == 'null') ? null : $request->almacene_id;
      $movimiento->stand_id = ($request->stand_id == 'null') ? null : $request->stand_id;
      $movimiento->nivel_id = ($request->nivel_id == 'null') ? null : $request->nivel_id;
      $movimiento->articulo_id = $request->articulo_id;
      Utilidades::auditar($movimiento, $movimiento->id);
      $movimiento->save();

      $stoke_art = DB::table('stock_articulos')->select('stock_articulos.*')
        ->where('stock_articulos.articulo_id', '=', $request->articulo_id)->where('stock_articulos.stocke_id', '=', $sp->stocke_id)
        ->first();
      if (!is_null($stoke_art))
      {
        $cantida_n = $stoke_art->cantidad + $request->cantidad;
        DB::table('stock_articulos')
          ->where([
            ['articulo_id',  '=',  $request->articulo_id],
            ['stocke_id', '=', $sp->stocke_id],
          ])
          ->update(['cantidad' => $cantida_n]);
      }
      else
      {
        $stokearticulo = new \App\StockArticulo();
        $stokearticulo->cantidad = $request->cantidad;
        $stokearticulo->articulo_id = $request->articulo_id;
        $stokearticulo->stocke_id = $sp->stocke_id;
        $stokearticulo->save();
      }
      DB::commit();
      // return $lote->nombre;
      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  /**
   * [verfinalizacionpartida Verfica que las partidas de la entrada son equivalentes a las partidas de la compras
   * y que hallan sido validados por calidad ]
   * @param  [type] $id [ID de la partida]
   * @return [type]     [status => true]
   */
  public function verfinalizacionpartida($id)
  {

    $partidas_entradas = DB::table('partidas_entradas')
      ->leftJoin('requisicion_has_ordencompras AS RHC', 'RHC.id', '=', 'partidas_entradas.req_com_id')
      ->leftJoin('ordenes_compras AS OC', 'OC.id', '=', 'RHC.orden_compra_id')
      ->select('partidas_entradas.*', 'OC.id AS idcompra')
      ->where('partidas_entradas.entrada_id', '=', $id)
      // ->whereIn('partidas_entradas.validacion_calidad',[0,2])
      // ->where('partidas_entradas.validacion_calidad','=','2')
      ->get();

    if (count($partidas_entradas) != 0)
    {

      $rhc = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*')
        ->where('requisicion_has_ordencompras.orden_compra_id', '=', $partidas_entradas[0]->idcompra)
        ->where('requisicion_has_ordencompras.condicion', '=', '0')->get();

      // $var = ':)';
      if (count($partidas_entradas) == count($rhc))
      {
        $entrada = \App\Entrada::where('id', '=', $id)->first();
        $entrada->estado = 2; //finalizado
        $entrada->save();
      }
    }

    return response()->json(array('status' => true));
    // $c =count($partidas_entradas) + count($rhc);
  }

  public function getStoke($req_com_id)
  {
    $stoke = DB::table('requisicion_has_ordencompras AS rhc')
      ->join('ordenes_compras AS oc', 'oc.id', 'rhc.orden_compra_id')
      ->join('proyectos AS p', 'p.id', 'oc.proyecto_id')
      ->join('stocks AS s', 's.proyecto_id', 'p.id')
      ->select('s.id as stocke_id', 'p.id AS proyecto_id')
      ->where('rhc.id', $req_com_id)
      ->first();

    return $stoke;
  }

  /**
   * [show busqueda de la tabla partidas_entradas por el $id recibido]
   * @param  Int $id [description]
   * @return Response   [description]
   */
  public function show($id)
  {
    $entradas = DB::table('partidas_entradas')
      //Datos pertenecientes a partidas entrdas
      ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
      ->leftJoin('ordenes_compras AS OC', 'OC.id', '=', 'partidas_entradas.req_com_id')
      ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
      ->leftJoin('requisicion_has_ordencompras AS RHC', function ($join)
      {
        $join->on('RHC.orden_compra_id', '=', 'OC.id')
          ->on('RHC.articulo_id', '=', 'A.id');
      })
      //Fin
      ->select(
        'partidas_entradas.*',
        'A.id AS aid',
        'A.descripcion AS ad',
        'A.marca AS amarca',
        'OC.id AS idcompra',
        'OC.folio AS foliocompra',
        'RHC.cantidad AS cantidadcompra',
        'E.id AS entradaid',
        'E.fecha AS fechaentrada'
      )
      ->where('partidas_entradas.id', '=', $id)
      ->get()->toArray();
    return response()->json($entradas);
  }

  /**
   * Agregar articulos para ingresarlos al almacen sin una orden de compra previa
   */
  public function guardarPartidaInterna(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    if ($request->pendiente == '1')
    {
      // return response()->json('si');
      $requi_id = '';
      $orden_id = '';
      $proyecto = DB::table('stocks')->join('proyectos as p', 'p.id', '=', 'stocks.proyecto_id')->select('stocks.*', 'p.id as p_ids')->where('stocks.id', '=', $request->stocke_id)->first();
      $requision_existente = \App\Requisicion::where('folio', '=', ('PENDIENTE-' . $proyecto->nombre))->first();
      $orden_existente = \App\Compras::where('folio', '=', ('PENDIENTE-' . $proyecto->nombre))->first();
      if (is_null($requision_existente))
      {
        $requision_e = new \App\Requisicion();
        $requision_e->folio = 'PENDIENTE-' . $proyecto->nombre;
        $requision_e->area_solicita_id = 1;
        $requision_e->fecha_solicitud = '2019-01-01';
        $requision_e->descripcion_uso = 'pendiente';
        $requision_e->tipo_compra = '1';
        $requision_e->proyecto_id = $proyecto->p_ids;
        $requision_e->solicita_empleado_id = '1';
        $requision_e->autoriza_empleado_id = '1';
        $requision_e->valida_empleado_id = '1';
        Utilidades::auditar($requision_e, $requision_e->id);
        $requision_e->save();
        $requi_id = $requision_e->id;
      }
      else
      {
        $requi_id = $requision_existente->id;
      }
      if (is_null($orden_existente))
      {
        $orden_e = new \App\Compras();
        $orden_e->folio = 'PENDIENTE-' . $proyecto->nombre;
        $orden_e->proveedore_id = 1;
        $orden_e->proyecto_id = $proyecto->p_ids;
        $orden_e->elabora_empleado_id = 1;
        $orden_e->autoriza_empleado_id = 1;
        Utilidades::auditar($orden_e, $orden_e->id);
        $orden_e->save();
        $orden_id = $orden_e->id;
      }
      else
      {
        $orden_id = $orden_existente->id;
      }

      $articulo_id = $request->articulo_id;
      $orden_compra_id = $request->orden_compra_id;

      $requisicion_has_compra = new Requisicionhasordencompras();
      $requisicion_has_compra->requisicione_id = $requi_id;
      $requisicion_has_compra->orden_compra_id  = $orden_id;
      $requisicion_has_compra->articulo_id = $request->articulo_id;
      $requisicion_has_compra->cantidad = $request->cantidad;
      $requisicion_has_compra->precio_unitario = $request->precio;
      $requisicion_has_compra->condicion = 0;
      $requisicion_has_compra->tipo_entrada = $request->tipo_entrada;
      Utilidades::auditar($requisicion_has_compra, $requisicion_has_compra->id);
      $requisicion_has_compra->save();

      if ($request->adicionales > 0)
      {
        $adicionales = new \App\Adicionales();
        $adicionales->req_has_comp = $requisicion_has_compra->id;
        $adicionales->cantidad = $request->adicionales;
        Utilidades::auditar($adicionales, $adicionales->id);
        $adicionales->save();
      }

      $partidaentrada = new PartidaEntrada();
      // $partidaentrada->fill($request->all());
      $partidaentrada->entrada_id = $request->entrada_id;
      $partidaentrada->articulo_id = $request->articulo_id;
      $partidaentrada->req_com_id = $requisicion_has_compra->id;
      if ($request->caducidad)
        $partidaentrada->caducidad = $request->caducidad;
      $partidaentrada->cantidad = $request->cantidad;
      $partidaentrada->stocke_id = $request->stocke_id;
      $partidaentrada->pendiente = $request->pendiente;
      Utilidades::auditar($partidaentrada, $partidaentrada->id);
      $partidaentrada->save();

      return response()->json(array('status' => true));
    }
    else
    {
      $articulo_id = $request->articulo_id;
      $orden_compra_id = $request->orden_compra_id;

      $requisicion_has_compra = new Requisicionhasordencompras();
      $requisicion_has_compra->requisicione_id = 1;
      $requisicion_has_compra->orden_compra_id  = $request->orden_compra_id;
      $requisicion_has_compra->articulo_id = $request->articulo_id;
      $requisicion_has_compra->cantidad = $request->cantidad;
      $requisicion_has_compra->precio_unitario = $request->precio;
      $requisicion_has_compra->condicion = 0;
      $requisicion_has_compra->tipo_entrada = $request->tipo_entrada;
      Utilidades::auditar($requisicion_has_compra, $requisicion_has_compra->id);
      $requisicion_has_compra->save();

      if ($request->adicionales > 0)
      {
        $adicionales = new \App\Adicionales();
        $adicionales->req_has_comp = $requisicion_has_compra->id;
        $adicionales->cantidad = $request->adicionales;
        Utilidades::auditar($adicionales, $adicionales->id);
        $adicionales->save();
      }

      $partidaentrada = new PartidaEntrada();
      // $partidaentrada->fill($request->all());
      $partidaentrada->entrada_id = $request->entrada_id;
      $partidaentrada->articulo_id = $request->articulo_id;
      $partidaentrada->req_com_id = $requisicion_has_compra->id;
      if ($request->caducidad)
        $partidaentrada->caducidad = $request->caducidad;
      $partidaentrada->cantidad = $request->cantidad;
      $partidaentrada->stocke_id = $request->stocke_id;
      // $partidaentrada->pendiente = $request->pendiente;
      Utilidades::auditar($partidaentrada, $partidaentrada->id);
      $partidaentrada->save();

      return response()->json(array('status' => true));
    }
  }

  /**
   * [guardarPrecio calcula el precio del lote entrante utilizando los datos de la orden de compra]
   * @param  Object $request [description]
   * @param  Array $lote    [description]
   * @return Response          [description]
   */
  public function guardarPrecio($request, $lote)
  {
    $orden_compra_id = $request->orden_compra_id;
    $precio_unitario = $request->precio_unitario;
    $articulo_id = $lote->articulo_id;
    $lote_id = $lote->id;
    // $partida_compra = \App\PartidaEntrada::where('orden_compra_id','=',$orden_compra_id);
    $requisioncompra = Requisicionhasordencompras::where('id', '=', $orden_compra_id)->first();
    $orden_compra = \App\Compras::where('id', '=', $requisioncompra->orden_compra_id)->first();
    $porcentaje_impuestos = 0;
    $porcentaje_descuento = 0;
    $precio_unitario_total = 0;
    $impuestos = \App\Impuesto::where('orden_compra_id', '=', $orden_compra->id)->first();
    if ($impuestos != null)
    {
      $cantidad_impuestos = \App\Impuesto::where('orden_compra_id', '=', $orden_compra->id)->select(DB::raw("SUM(porcentaje) AS total_impuestos"))->first();
      $porcentaje_impuestos = $cantidad_impuestos->total_impuestos;
    }
    $porcentaje_descuento = $orden_compra->descuento;

    $precio_unitario_total = ($precio_unitario - (($precio_unitario / 100) * $porcentaje_descuento));
    $precio_unitario_total = ($precio_unitario_total + (($precio_unitario_total / 100) * $porcentaje_impuestos));
    $precio = new \App\Precios();
    $precio->precio_unitario = $precio_unitario_total;
    $precio->lote_id = $lote_id;
    $precio->save();

    return response()->json(array('status' => true));
  }

  /**
   * Actualiza el precio unitario para los articulos que fueron ingresados por entradas internas
   * No se aplican impuesto ni descuentos a las entradas internas
   */
  public function actualizarPrecioEntradaInterna(Request $request)
  {
    $rhc = Requisicionhasordencompras::find($request->req_com_id);
    $rhc->precio_unitario = $request->precio_unitario;
    $rhc->save();

    $partidas_e = PartidaEntrada::where('id', $request->partida_entrada_id)->first();
    $partidas_e->precio_unitario = $request->precio_unitario;
    $partidas_e->save();

    $adicionales = \App\Adicionales::where('req_has_comp', '=', $rhc->id)->first();
    if (!is_null($adicionales))
    {
      $adicionales->cantidad = $request->adicionales;
      $adicionales->save();
    }
    else
    {
      $adicionales_nuevo = new \App\Adicionales();
      $adicionales_nuevo->req_has_comp = $rhc->id;
      $adicionales_nuevo->cantidad = $request->adicionales;
      $adicionales_nuevo->save();
    }

    $lote = DB::table('lotes')->select('lote_almacen.*')
      ->join('lote_almacen', 'lote_almacen.lote_id', '=', 'lotes.id')
      ->where('lotes.entrada_id', '=', $request->partida_entrada_id)
      ->first();

    if (!is_null($lote))
    {
      Precios::where('lote_id', $lote->id)
        ->update(['precio_unitario' => $request->precio_unitario]);

      LoteAlmacen::where('lote_id', $lote->id)
        ->update(['comentario' => $request->comentario]);
    }

    return response()->json(array('status' => true));
  }

  public function obtenerComentarioLoteAlmacen(Request $request)
  {
    $lote = DB::table('lotes')->select('lote_almacen.*')
      ->join('lote_almacen', 'lote_almacen.lote_id', '=', 'lotes.id')
      ->where('lotes.entrada_id', '=', $request->partida_entrada_id)
      ->first();

    $comentario = '';
    if (!is_null($lote))
    {
      $loteAlmacen = LoteAlmacen::where('lote_id', $lote->id)
        ->first();

      if (!is_null($loteAlmacen))
        $comentario = $loteAlmacen->comentario;
    }

    return response()->json(array('status' => true, 'comentario' => $comentario));
  }

  public function busqueda()
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = PartidaEntrada::join('articulos', 'articulos.id', '=', 'partidas_entradas.articulo_id')
      ->join('requisicion_has_ordencompras', 'requisicion_has_ordencompras.id', '=', 'partidas_entradas.req_com_id')
      ->select(
        [
          'articulos.id', 'articulos.codigo', 'articulos.nombre', 'articulos.descripcion', 'articulos.marca', 'articulos.calidad_id', 'partidas_entradas.pendiente AS pendiente', 'partidas_entradas.cantidad', 'partidas_entradas.req_com_id', 'partidas_entradas.id AS PartEntr', 'requisicion_has_ordencompras.requisicione_id AS ReqAntigua'
        ]
      )->where('articulos.condicion', '=', '1')->where('partidas_entradas.pendiente', '=', '1');

    if (isset($query) && $query)
    {
      $data = $byColumn == 1 ?
        $this->busqueda_filterByColumn2($data, $query) :
        $this->busqueda_filter2($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
      ->skip($limit * ($page - 1));

    if (isset($orderBy))
    {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }


    $results = $this->tipoCalidad($data->get());

    return [
      'data' => $results,
      'count' => $count,
    ];
  }

  public function tipoCalidad($data)
  {
    $arreglo_final = [];
    foreach ($data as $key => $value)
    {
      $tipo_calidad = \App\TipoCalidad::where('id', '=', $value->calidad_id)->first();
      $arreglo_final[] = array_merge($value->toArray(), [
        'calidad' => $tipo_calidad == null ? '' : $tipo_calidad->nombre,
        'descal' => $tipo_calidad == null ? '' : $tipo_calidad->descripcion
      ]);
    }
    return $arreglo_final;
  }

  protected function busqueda_filterByColumn2($data, $queries)
  {
    $queries = json_decode($queries, true);

    return $data->where(function ($q) use ($queries)
    {
      foreach ($queries as $field => $query)
      {
        $_field = $field;

        if (is_string($query))
        {
          $q->where($_field, 'LIKE', "%{$query}%");
        }
        else
        {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  protected function busqueda_filter2($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields)
    {
      foreach ($fields as $index => $field)
      {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
  }

  // public function eliminar($id)
  // {
  //   $lote_almacen = LoteAlmacen::
  //   ->join('lotes AS l','l.id','lote_almacen.lote_id')
  //   ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
  //   ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
  //   ->select('lote_almacen.*','l.id AS l_id','pe.id AS pe_id','rhoc.id AS rhoc_id')
  //   ->where('id',$id)->first();
  //
  //   $rhoc = DB::table('requisicion_has_ordencompras AS rhoc')
  //   ->where('id',$lote_almacen->rhoc_id)->first();
  //
  //   if ($rhoc->cantidad_entrada == 0) {
  //
  //   }
  //
  // }

  public function almacenActualizacion(Request $request)
        {
          try {
            DB::beginTransaction();

            $partida_entrada = \App\PartidaEntrada::where('id',$request->id)->first();
            $partida_entrada->almacene_id = ($request->almacen_id == 'null') ? null : $request->almacen_id;
            $partida_entrada->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
            $partida_entrada->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
            $partida_entrada->update();

            $lote = \App\Lote::where('entrada_id',$partida_entrada->id)->first();

            $lote_almacen = \App\LoteAlmacen::where('lote_id',$lote->id)->first();
            $lote_almacen->almacene_id = ($request->almacen_id == 'null') ? null : $request->almacen_id;
            $lote_almacen->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
            $lote_almacen->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
            $lote_almacen->update();


            $existencias = \App\Existencia::where('id_lote',$lote->id)->where('articulo_id',$lote->articulo_id)->first();
            // $existencias->id_lote = $lote->id;
            // $existencias->articulo_id = $request->articulo_id;
            $existencias->almacene_id = ($request->almacen_id == 'null') ? null : $request->almacen_id;
            $existencias->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
            $existencias->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
            // $existencias->cantidad = $request->cantidad;
            Utilidades::auditar($existencias,$existencias->id);
            $existencias->update();

            $sp = $this->getStoke($partida_entrada->req_com_id);

            $hoy = date("Y-m-d");
            $hora = date("H:i:s");
            $movimiento = new \App\Movimiento();
            $movimiento->cantidad = $lote_almacen->cantidad;
            $movimiento->fecha = $hoy;
            $movimiento->hora = $hora;
            $movimiento->tipo_movimiento = 'CambioA';
            $movimiento->folio = 'CambioA-'.$sp->proyecto_id.$sp->stocke_id;
            $movimiento->proyecto_id =  $sp->proyecto_id;
            $movimiento->lote_id =  $lote_almacen->id;
            $movimiento->stocke_id =  $sp->stocke_id;
            $movimiento->almacene_id = ($request->almacen_id == 'null') ? null : $request->almacen_id;
            $movimiento->stand_id = ($request->stand_id == 'null') ? null:$request->stand_id;
            $movimiento->nivel_id = ($request->nivel_id == 'null') ? null:$request->nivel_id;
            $movimiento->articulo_id = $lote->articulo_id;
            Utilidades::auditar($movimiento,$movimiento->id);
            $movimiento->save();

            DB::commit();
            return response()->json(['status' => true]);
          } catch (\Throwable $e) {
            DB::rollBack();
            Utilidades::errors($e);
          }
        }


  public function Almacenes()
  {
    try
    {
      $alamcaenes = Almacene::where("condicion", 1)->orderBy("nombre")->get();
      return response()->json(["status" => true, "almacenes" => $alamcaenes]);
    }
    catch (Exception $e)
    {
      return response()->json(["status" => false]);
    }
  }

  public function Categorias()
  {
    try
    {
      $categorias = DB::table("categorias")
      ->orderBy("nombre")
      ->get();
      return response()->json(["status" => true, "categorias" => $categorias]);
    }
    catch (Exception $e)
    {
      return response()->json(["status" => false,$e->getMessage()]);
    }
  }
}
