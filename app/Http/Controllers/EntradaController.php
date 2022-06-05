<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Entrada;
use App\Articulo;
use App\PartidaEntrada;
use App\FacturasEntradas;
use Validator;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;


class EntradaController extends Controller
{
  /**
  * [index consulta de la tabla 'entradas']
  * @return [entrada]
  */
  public function index()
  {
    $entrada = DB::table('entradas')
    ->leftJoin('ordenes_compras AS OC','OC.id','=','entradas.orden_compra_id')
    ->select('entradas.*','OC.folio AS foliocompra','OC.proyecto_id')
    ->where('entradas.condicion','1')
    ->orderBy('entradas.fecha','desc');

    return $this->busqueda($entrada);
  }

  /**
  * [store Inserta un nuevo registro en la tabla Entradas]
  * @param  Request $request [request]
  * @return Response         [status => true]
  */
  public function store(Request $request)
  {
    try{
      $ids = Auth::user();

      $fecha = date('Y-m-d');
      $entrada_busqueda = Entrada::where('fecha',$request->fecha)->where('orden_compra_id',$request->orden_compra_id)->first();
      if (isset($entrada_busqueda) == true) {
        return response()->json(array(
          'status' => true,
          'id_entrada' => $entrada_busqueda->id,
        ));
      }else {
        DB::beginTransaction();
        $entrada = new Entrada();
        $entrada->fecha = $request->fecha;
        $entrada->comentarios = $request->comentarios;
        $entrada->formato_entrada = $this->getFolioEntrada($request->orden_compra_id);
        $entrada->tipo_adq_id = $request->tipo_adq_id;
        $entrada->tipo_entrada_id = $request->tipo_entrada_id;
        $entrada->empleado_almacen_id = $ids->empleado_id;
        $entrada->empleado_usuario_id = 147;
        $entrada->orden_compra_id = $request->orden_compra_id;
        Utilidades::auditar($entrada, $entrada->id);
        $entrada->save();
        DB::commit();
        return response()->json(array(
          'status' => true, 'id_entrada' => $entrada->id,
        ));
      }
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  /**
  * [update Actualiza el registro en la tabla Entradas]
  * @param  Request $request [request]
  * @param  int  $id      [ID entrada]
  * @return Response       [status => true]
  */
  public function update(Request $request, $id)
  {
    try{
      DB::beginTransaction();
      $entrada = Entrada::findOrFail($request->id);
      $entrada->fill($request->all());
      Utilidades::auditar($entrada, $entrada->id);
      $entrada->save();
      DB::commit();

      return response()->json(array(
        'status' => true
      ));
    } catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
    }
  }

  /**
  * [edit Actualiza el campo condicion en la tabla entrada de la BD]
  * @param  Int $id [description]
  * @return Array $entrada [description]
  */
  public function edit($id)
  {
    try{
      $entrada = Entrada::findOrFail($id);
      if ($entrada->condicion==0) {
        $entrada->condicion = 1;
      }else{
        $entrada->condicion = 0;
      }
      Utilidades::auditar($entrada, $entrada->id);
      $entrada->update();
      return $entrada;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [show busqueda de la tabla partidas_entradas por el $id recibido]
  * @param  Int $id [description]
  * @return Response   [Json]
  */
  public function show($id)
  {
    $partidas_entradas = DB::table('partidas_entradas')
    //Datos pertenecientes a partidas entrdas
    ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    ->leftJoin('adicionales AS AD','AD.req_has_comp','=','RHC.id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    ->leftJoin('requisiciones AS p', 'p.id', '=' , 'RHC.requisicione_id')
    ->leftJoin('proyectos AS pr','pr.id','=','p.proyecto_id')
    ->leftJoin('stocks AS S','S.proyecto_id','=','pr.id')

    ->select('partidas_entradas.*',
    'A.id AS aid',
    'A.descripcion AS ad',
    'A.marca AS amarca',
    'OC.id AS idcompra',
    'OC.folio AS foliocompra',
    'RHC.cantidad AS cantidadcompra',
    'partidas_entradas.precio_unitario',
    'E.id AS entradaid',
    'E.fecha AS fechaentrada',
    'p.proyecto_id AS proyecto_id',
    'pr.nombre AS proyectonombre',
    'S.nombre AS stokenombre',
    'S.id AS stokeid',
    'RHC.tipo_entrada',
    'AD.cantidad AS cantidad_adicional' )
    ->where('partidas_entradas.entrada_id', '=', $id)
    ->where('RHC.articulo_id','!=','null')
    ->orderByRaw('id DESC')->get()->toArray();



    return response()->json($partidas_entradas);

  }

  public function partidasEntradas($id)
  {
    // code...
    $partidas_entradas = DB::table('partidas_entradas')
    ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    ->leftJoin('adicionales AS AD','AD.req_has_comp','=','RHC.id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    ->leftJoin('requisiciones AS p', 'p.id', '=' , 'RHC.requisicione_id')
    ->leftJoin('proyectos AS pr','pr.id','=','p.proyecto_id')
    ->leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
    ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
    ->leftJoin('movimientos AS mov','mov.lote_id','=','la.id')
    ->leftJoin('stocks AS S','S.proyecto_id','=','pr.id')

    ->select('partidas_entradas.*',
    'A.id AS aid',
    'A.descripcion',
    'A.codigo',
    'A.marca',
    'OC.id AS idcompra',
    'OC.folio AS foliocompra',
    'RHC.cantidad AS cantidadcompra',
    'RHC.precio_unitario',
    'E.id AS entradaid',
    'mov.fecha AS fechaentrada',
    'p.proyecto_id AS proyecto_id',
    'pr.nombre AS proyectonombre',
    'S.nombre AS stokenombre',
    'S.id AS stokeid',
    'RHC.tipo_entrada',
    'AD.cantidad AS cantidad_adicional' )
    ->where('partidas_entradas.entrada_id','=',$id)->orderByRaw('id DESC')->where('RHC.articulo_id','!=','null');
    return $partidas_entradas;
  }

  public function busquedaEntradaInterna()
  {
    $id = 1;
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $this->partidasEntradas($id);

    if (isset($query) && $query) {
      $data = $byColumn == 1 ?
      $this->busqueda_filterByColumn($data, $query) :
      $this->busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
    ->skip($limit * ($page - 1));


    if (isset($orderBy)) {
      $direction = $ascending == 1 ? 'ASC' : 'DESC';
      $data->orderBy($orderBy, $direction);
    }
    $results = $data->get();


    return [
      'data' => $results,
      'count' => $count,
    ];
  }


  protected function busqueda_filterByColumn($data, $queries)
  {
    $queries = json_decode($queries, true);

    return $data->where(function ($q) use ($queries) {
      foreach ($queries as $field => $query) {
        $_field = $field;

        if (is_string($query)) {
          $q->where($_field, 'LIKE', "%{$query}%");
        } else {
          $start = Carbon::createFromFormat('Y-m-d', substr($query['start'], 0, 10))->startOfDay();
          $end = Carbon::createFromFormat('Y-m-d', substr($query['end'], 0, 10))->endOfDay();

          $q->whereBetween($_field, [$start, $end]);
        }
      }
    });
  }

  protected function busqueda_filter($data, $query, $fields)
  {
    return $data->where(function ($q) use ($query, $fields) {
      foreach ($fields as $index => $field) {
        $method = $index ? 'orWhere' : 'where';
        $q->{$method}($field, 'LIKE', "%{$query}%");
      }
    });
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
    ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    ->select('partidas_entradas.*','OC.id AS idcompra')
    ->where('partidas_entradas.entrada_id','=',$id)
    ->whereIn('partidas_entradas.validacion_calidad',[0,2])
    ->get();

    if (count($partidas_entradas) != 0) {

      $rhc = DB::table('requisicion_has_ordencompras')->select('requisicion_has_ordencompras.*')
      ->where('requisicion_has_ordencompras.orden_compra_id','=',$partidas_entradas[0]->idcompra)
      ->where('requisicion_has_ordencompras.condicion','=','0')->get();

      if (count($partidas_entradas) == count($rhc)) {
        $entrada = \App\Entrada::where('id','=',$id)->first();
        $entrada->estado = 2;//finalizado
        $entrada->save();

      }
    }

    return response()->json(array('status' => true));
    // $c =count($partidas_entradas) + count($rhc);
  }

  /**
  * [activar Actualiza el campo condicion en la tabla entrada de la BD]
  * @param  Int $id [description]
  */
  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $entrada = Entrada::findOrFail($request->id);
    $entrada->condicion = '1';
    $entrada->save();
  }

  /**
  * [verordenescompra Obtiene los registros de la tabla requisicion_has_ordencompras]
  * @param  Response [rhc]
  */
  public function verordenescompras()
  {
    $rhc = DB::select("SELECT  oc.*  FROM  requisicion_has_ordencompras rho
      JOIN ordenes_compras oc on oc.id = rho.orden_compra_id
      WHERE  rho.articulo_id IS NOT NULL
      AND  rho.condicion = 1 AND oc.condicion = 2
      GROUP BY oc.id");

      return response()->json($rhc);
    }

    public function revisionfactura($id)
    {
      $entrada = Entrada::where('id',$id)->first();
      $entrada->estado = 3;
      $entrada->save();
      return response()->json(array('status' => true));
    }

    public function GetPartidas($id)
    {
      $partidas_compras = DB::table('requisicion_has_ordencompras AS rhoc')
      ->join('articulos AS a','a.id','rhoc.articulo_id')
      ->select('rhoc.id','a.descripcion','a.unidad',
      DB::raw('(CASE
      WHEN rhoc.cantidad_entrada = 0 THEN rhoc.cantidad
      ELSE rhoc.cantidad_entrada
      END) AS cantidad'),
      'a.id AS articulo_id')
      ->where('rhoc.articulo_id','!=','NULL')
      ->where('orden_compra_id',$id)
      ->where('rhoc.condicion','1');

      return $this->busqueda($partidas_compras);
    }

    public function ActPartidas($id)
    {
      $partidas_entradas =DB::table('partidas_entradas AS pe')
      ->join('articulos AS a','a.id','pe.articulo_id')
      ->select('pe.id','a.descripcion','a.unidad','pe.cantidad','pe.almacene_id','nivel_id','stand_id',
      'a.id AS articulo_id')
      ->where('pe.entrada_id',$id);

      return $this->busqueda($partidas_entradas);
    }

    public function busqueda($dato)
    {
      extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

      $data = $dato;

      if (isset($query) && $query) {
        $data = $byColumn == 1 ?
        Utilidades::busqueda_filterByColumn($data, $query) :
        Utilidades::busqueda_filter($data, $query, $fields);
      }

      $count = $data->count();

      $data->limit($limit)
      ->skip($limit * ($page - 1));

      if (isset($orderBy)) {
        $direction = $ascending == 1 ? 'ASC' : 'DESC';
        $data->orderBy($orderBy, $direction);
      }

      $results = $data->get();

      return [
        'data' => $results,
        'count' => $count,
      ];
    }

    public function getFolioEntrada($oc)
    {

      $proyecto = DB::table('ordenes_compras AS oc')
      ->select('oc.proyecto_id AS proyecto')
      ->where('oc.id',$oc)
      ->first();

      $entra = DB::table('entradas AS e')
      ->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
      ->select('e.*')
      ->where('oc.proyecto_id',$proyecto->proyecto)
      ->where('e.condicion','1')->orderBy('e.fecha')->get();

      $contador = count($entra) + 1;

      return str_pad(($contador), 4, "0", STR_PAD_LEFT);
    }

  }
