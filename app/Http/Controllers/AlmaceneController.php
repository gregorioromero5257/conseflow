<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Almacene;
use App\Stand;
use App\Nivele;
use App\Existencia;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InventarioExport;
use App\Exports\InventariosExport;
use App\Exports\InventariosPExport;
use App\Exports\TrazabilidadExport;
use Validator;
use \App\Http\Helpers\Utilidades;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;



use Illuminate\Support\Facades\DB;

class AlmaceneController extends Controller
{
  /**
  * [protected reglas definidas para el guardado y actualizacion de registros]
  * @var [type]
  */
  protected $rules = array(
    'nombre' => 'required|max:50',
  );

  protected $rulesStand = array(
    'stand' => 'required|max:50',
  );

  protected $rulesNivel = array(
    'nivel' => 'required|max:50',
  );
  /**************************************/

  /**
  * [index Selecciona del modelo Almacene todos los registros en el ademas de verificar la ubicacion del usuario logeado]
  * @return Response [description]
  */
  public function index()
  {
    $ubicacion = Utilidades::ubicacion();
    if ($ubicacion == null) {
      $almacenes = Almacene::select('almacenes.id','almacenes.nombre as nombre',
      'stands.id as stand_id','stands.Nombre as stand','niveles.id as nivel_id',
      'niveles.Nombre as nivel','almacenes.condicion', 'almacenes.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
      ->leftJoin('stands', 'stands.almacene_id', '=', 'almacenes.id')
      ->leftJoin('niveles', 'niveles.stande_id', '=', 'stands.id')
      ->leftJoin('tipo_ubicacion', 'almacenes.ubicacion_id', '=', 'tipo_ubicacion.id')
      ->orderBy('id', 'asc')->get()->toArray();

    }
    elseif($ubicacion != null){
      $almacenes = Almacene::select('almacenes.id','almacenes.nombre as nombre',
      'stands.id as stand_id','stands.Nombre as stand','niveles.id as nivel_id',
      'niveles.Nombre as nivel','almacenes.condicion', 'almacenes.ubicacion_id', 'tipo_ubicacion.nombre AS ubicacion')
      ->leftJoin('stands', 'stands.almacene_id', '=', 'almacenes.id')
      ->leftJoin('niveles', 'niveles.stande_id', '=', 'stands.id')
      ->leftJoin('tipo_ubicacion', 'almacenes.ubicacion_id', '=', 'tipo_ubicacion.id')
      ->where('almacenes.ubicacion_id','=',$ubicacion)
      ->orderBy('id', 'asc')->get()->toArray();
    }


    return response()->json($almacenes);
  }
  public function inventario()
  {


    $exx = Existencia::select('existencias.*','lotes.cantidad AS lt_cantidad',
    'articulos.nombre as articulo','articulos.descripcion as descripcion', 'articulos.codigo as codigo',
     'articulos.marca as marca','articulos.unidad as unidad','almacenes.nombre as almacen',
     'lotes.nombre as lote','stands.nombre as stand','niveles.nombre as nivel',
     'tipo_ubicacion.nombre AS ubicacion','stocks.nombre as stock','grupos.nombre as grupo',
     'categorias.nombre as categoria','partidas_entradas.precio_unitario')
    ->Join('lotes', 'lotes.id', '=', 'existencias.id_lote')
    ->join('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
    ->Join('lote_almacen',  'lote_almacen.lote_id', '=','lotes.id')
    ->Join('stocks', 'stocks.id', '=', 'lote_almacen.stocke_id')
    ->Join('articulos', 'articulos.id', '=','existencias.articulo_id')
    ->Join('almacenes', 'almacenes.id', '=','existencias.almacene_id')
    ->Join('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'almacenes.ubicacion_id')
    ->leftJoin('niveles', 'niveles.id', '=', 'existencias.nivel_id')
    ->leftJoin('stands', 'stands.id', '=', 'existencias.stand_id')
    ->leftJoin('grupos', 'grupos.id', '=', 'articulos.grupo_id')
    ->leftJoin('categorias', 'categorias.id', '=', 'grupos.categoria_id')
    ->orderBy('id', 'ASC')->get()->toArray();
    return response()->json($exx);
  }

  /**
  * [ver Selecciona los registro del modelo Almacene ademas de verificar la ubicación del usuario logeado]
  * @return Response [description]
  */
  public function ver()
  {
    $ubicacion = Utilidades::ubicacion();
    if ($ubicacion == null) {
      $almacenes = Almacene::select('almacenes.*')->orderBy("nombre")
      ->get()->toArray();
    }
    else {
      $almacenes = Almacene::select('almacenes.*')
      ->where('almacenes.ubicacion_id','=',$ubicacion)
      ->orderBy("nombre")
      ->get()->toArray();
    }

    return response()->json($almacenes);
  }

  /**
  * [verstand Selecciona los registros del modelo Stand apartir del almacen_id recibido]
  * @param  Int $id [description]
  * @return Response     [description]
  */
  public function verstand($id)
  {
    $almacenes = \App\Stand::select('stands.*')
    ->leftJoin('almacenes AS A','A.id','=','stands.almacene_id')
    ->where('stands.almacene_id','=',$id)
    ->get();

    return response()->json($almacenes);
  }

  /**
  * [vernivel Selecciona los registros del modelo Nivele apartir del stande_id recibido ]
  * @param  Int $id [description]
  * @return Response     [description]
  */
  public function vernivel($id)
  {
    $almacenes = \App\Nivele::select('niveles.*')
    ->leftJoin('stands AS S','S.id','=','niveles.stande_id')
    ->where('niveles.stande_id','=',$id)
    ->get();

    return response()->json($almacenes);
  }

  /**
  * [store Guarda un registro en lel modelo Almacene]
  * @param  Request $request [description]
  * @return Response           [status = true]
  */
  public function store(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      $this->rules['nombre'] = 'required|unique:almacenes,nombre,0,id|max:50';
      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      $almacen = new Almacene();
      $almacen->nombre = $request->nombre;
      $almacen->ubicacion_id = $request->ubicacion_id;
      $almacen->condicion = '1';
      Utilidades::auditar($almacen, $almacen->id);
      $almacen->save();
      return response()->json(array(
        'status' => true
      ));
    }  catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }

  /**
  * [update Actualiza un registro del modelo Almacene]
  * @param  Request $request [description]
  * @return Response           [Status = true]
  */
  public function update(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      $this->rules['nombre'] = 'required|unique:almacenes,nombre,'.$request->id.',id|max:50';
      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      $almacen = Almacene::find($request->id);
      $almacen->nombre = $request->nombre;
      $almacen->ubicacion_id = $request->ubicacion_id;
      $almacen->condicion = '1';
      Utilidades::auditar($almacen, $almacen->id);
      $almacen->save();
      return response()->json(array(
        'status' => true
      ));
    }   catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }


  }

  /**
  * [desactivar Actualiza el campo condicion a 0 de un registro en el modelo Almacene]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function desactivar(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $almacen = Almacene::findOrFail($request->id);
    $almacen->condicion = '0';
    Utilidades::auditar($almacen, $almacen->id);
    $almacen->save();
        } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [activar Actualiza el campo condición a 1 de un registro en el modelo Almacene]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function activar(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $almacen = Almacene::findOrFail($request->id);
    $almacen->condicion = '1';
    Utilidades::auditar($almacen, $almacen->id);
    $almacen->save();
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [registrarStand Guarda un registro en el modelo Stand]
  * @param  Request $request [description]
  * @return Array           [description]
  */
  public function registrarStand(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $stand = new Stand();
    $stand->fill($request->all());
    Utilidades::auditar($stand, $stand->id);
    $stand->save();
    return ['stands' => $stand];
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [actualizarStand Actualiza un registro en el modelo Stand]
  * @param  Request $request [description]
  * @return Array           [description]
  */
  public function actualizarStand(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $stand = Stand::find($request->id);
    $stand->fill($request->all());
    Utilidades::auditar($stand, $stand->id);
    $stand->save();
    return ['stands' => $stand];
      } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [registrarNivel Guarda un registro en el modelo Nivele]
  * @param  Request $request [description]
  * @return Array           [description]
  */
  public function registrarNivel(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $nivel = new Nivele();
    $nivel->Nombre = $request->Nombre;
    $nivel->stand_id = $request->stand_id;
    $nivel->condicion = 1;
    Utilidades::auditar($nivel, $nivel->id);
    $nivel->save();
    return ['niveles' => $nivel];
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [actualizarNivel Actualiza un registro en el modelo Nivele]
  * @param  Request $request [description]
  * @return Array           [description]
  */
  public function actualizarNivel(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $nivel = Nivele::find($request->id);
    $nivel->fill($request->all());
    Utilidades::auditar($nivel, $nivel->id);
    $nivel->save();
    return ['niveles' => $nivel];
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [getList Obtiene todos los registros del modelo Almacene]
  * @return Response [description]
  */
  public function getList()
  {
    $almacenes = Almacene::select('id', 'nombre')->orderBy('id', 'desc')->get()->toArray();
    return response()->json($almacenes);
  }

  /**
  * [storeStand Guarda un registro €n el modelo Stand]
  * @param  Request $request [description]
  * @return [type]           [description]
  */
  public function storeStand(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rulesStand);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $stand = new Stand();
    $stand->nombre = $request->stand;
    $stand->almacene_id = $request->almacen_id;
    $stand->condicion = '1';
    Utilidades::auditar($stand, $stand->id);
    $stand->save();
    return response()->json(array(
      'status' => true
    ));
   } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [updateStand Actualiza un registro en el modelo Stand]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function updateStand(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rulesStand);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $stand = Stand::find($request->id);
    $stand->nombre = $request->stand;
    $stand->almacene_id = $request->almacen_id;
    $stand->condicion = '1';
    Utilidades::auditar($stand, $stand->id);
    $stand->save();
    return response()->json(array(
      'status' => true
    ));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [getListStand Obtiene todos los registros del modelo Stand]
  * @param  Request $request [description]
  * @param  Int  $id      [description]
  * @return Response           [description]
  */
  public function getListStand(Request $request, $id)
  {
    $almacenes = Stand::select('id', 'nombre')
    ->where('almacene_id', $id)
    ->orderBy('id', 'desc')
    ->get()
    ->toArray();
    return response()->json($almacenes);
  }

  /**
  * [storeNivel Guarda un registro en el modelo Nivel]
  * @param  Request $request [description]
  * @return [type]           [description]
  */
  public function storeNivel(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rulesNivel);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $nivel = new Nivele();
    $nivel->nombre = $request->nivel;
    $nivel->stande_id = $request->stand_id;
    $nivel->condicion = '1';
    Utilidades::auditar($nivel, $nivel->id);
    $nivel->save();
    return response()->json(array(
      'status' => true
    ));
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function updateNivel(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');

    $validator = Validator::make($request->all(), $this->rulesNivel);

    if ($validator->fails()) {
      return response()->json(array(
        'status' => false,
        'errors' => $validator->errors()->all()
      ));
    }

    $nivel = Nivele::find($request->id);
    $nivel->nombre = $request->nivel;
    $nivel->stande_id = $request->stande_id;
    $nivel->condicion = '1';
    Utilidades::auditar($nivel, $nivel->id);
    $nivel->save();
    return response()->json(array(
      'status' => true
    ));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  /**
  * [requisicionesporautorizar Obtiene todas la requisiciones con estado_id = 3 para se editados en el dashboard de almacen]
  * @return Response [description]
  */
  public function requisicionesalmacen()
  {

    $requisiciones = DB::table('requisiciones')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
    ->select('requisiciones.*','p.nombre_corto AS nombrep',
    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_autoriza"))
    ->where('requisiciones.estado_id','=','3')
    // ->orWhere('requisiciones.pendiente','=','1')
    ->get();
    $arreglo = [];
    foreach ($requisiciones as $key => $value) {

      $comentario = \App\IncidenciasRequisiciones::where('requisicion_id',$value->id)
      ->where('pda','0')->where('articulo_servicio_id','0')->where('activo','1')->first();

      $arreglo [] = [
        'r' => $value,
        'comentario'  => $comentario,
      ];
    }

    return response()->json($arreglo);
  }

  /**
  * [requisicionesporautorizar Obtiene todas la requisiciones con estado_id = 3 para se editados en el dashboard de almacen]
  * @return Response [description]
  */
  public function requisicionesalmacenpendientes()
  {

    $requisiciones = DB::table('requisiciones')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
    ->select('requisiciones.*','p.nombre_corto AS nombrep',
    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_autoriza"))
    // ->where('requisiciones.estado_id','=','3')
    ->where('requisiciones.pendiente','=','1')
    ->get();

    return response()->json($requisiciones);
  }

    public function excel(Request $request, $id)
    {
        ini_set('max_execution_time', 1000);
        return Excel::download(new InventarioExport($id), 'ReporteInventario.xlsx');
        // return (new QuincenaExport)->download('invoices.xlsx');
    }

    public function excelGeneral()
    {
        ini_set('max_execution_time', 1000);
        return Excel::download(new InventariosExport(), 'ReporteInventarioGeneral.xlsx');
        // return (new QuincenaExport)->download('invoices.xlsx');
    }

    public function excelProyecto()
    {
        ini_set('max_execution_time', 1000);
        return Excel::download(new InventariosPExport(), 'ReporteInventarioProyecto.xlsx');
        // return (new QuincenaExport)->download('invoices.xlsx');
    }

    public function buscarInventario($id)
    {
      $valores = explode('&',$id);
      $fecha_uno = $valores[0];
      $fecha_dos = $valores[1];

      $exx = Existencia::select('existencias.*','lotes.cantidad AS lt_cantidad',
      'articulos.nombre as articulo','articulos.descripcion as descripcion', 'articulos.codigo as codigo',
       'articulos.marca as marca','articulos.unidad as unidad','almacenes.nombre as almacen',
       'lotes.nombre as lote','stands.nombre as stand','niveles.nombre as nivel',
       'tipo_ubicacion.nombre AS ubicacion','stocks.nombre as stock','grupos.nombre as grupo',
       'categorias.nombre as categoria','partidas_entradas.precio_unitario')
      ->Join('lotes', 'lotes.id', '=', 'existencias.id_lote')
      ->join('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
      ->Join('lote_almacen',  'lote_almacen.lote_id', '=','lotes.id')
      ->Join('movimientos AS m','m.lote_id','=','lote_almacen.id')
      ->Join('stocks', 'stocks.id', '=', 'lote_almacen.stocke_id')
      ->Join('articulos', 'articulos.id', '=','existencias.articulo_id')
      ->Join('almacenes', 'almacenes.id', '=','existencias.almacene_id')
      ->Join('tipo_ubicacion', 'tipo_ubicacion.id', '=', 'almacenes.ubicacion_id')
      ->leftJoin('niveles', 'niveles.id', '=', 'existencias.nivel_id')
      ->leftJoin('stands', 'stands.id', '=', 'existencias.stand_id')
      ->leftJoin('grupos', 'grupos.id', '=', 'articulos.grupo_id')
      ->leftJoin('categorias', 'categorias.id', '=', 'grupos.categoria_id')
      ->whereBetween('m.fecha',[$fecha_uno,$fecha_dos])
      ->orderBy('id', 'ASC')->get()->toArray();

      return response()->json($exx);

    }

    public function ValeRetorno($id)
    {
          $ids = Auth::id();

            $salidas = DB::table('salidas_retorno')

            ->select('salidas.*',
          DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS entrega"),
          DB::raw("CONCAT(ER.nombre,' ',ER.ap_paterno,' ',ER.ap_materno) AS recibe"),
          DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS solicita"),
          DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS autoriza"))
          ->leftJoin('empleados AS EE', 'EE.id', '=', 'salidas.empleado_entrega_id')
          ->leftJoin('empleados AS ER', 'ER.id', '=', 'salidas.empleado_recibe_id')
          ->leftJoin('empleados AS EA', 'EA.id', '=', 'salidas.empleado_autoriza_id')
          ->leftJoin('empleados AS ES', 'ES.id', '=', 'salidas.empleado_id')
          ->leftJoin('proyectos', 'proyectos.id', '=', 'salidas.proyecto_id')
          ->leftJoin('tipo_salidas', 'tipo_salidas.id', '=', 'salidas.tiposalida_id')
          ->where('salidas.id','=',$id)->first();

          $proyecto = \App\Proyecto::where('id','=',$salidas->proyecto_id)->first();

          $partidas = DB::table('partidas')
          ->leftJoin('lote_almacen AS LA','LA.id','=','partidas.lote_id')
          ->leftJoin('articulos AS A','A.id','=','LA.articulo_id')
          ->select('partidas.*','A.descripcion AS descripcion','A.unidad')
          ->where('salida_id','=',$salidas->id)->where('tiposalida_id','=',$salidas->tiposalida_id)->get();

          $solicita_datos = \App\Empleado::
          join('puestos AS p','p.id','=','empleados.puesto_id')
          ->where('empleados.id',$salidas->empleado_id)
          ->select('p.*')->first();


          $count =1;
          $tamanio = 21 - count($partidas);
          $pdf = PDF::loadView('pdf.retornonew', compact('solicita_datos','salidas','proyecto','partidas','ids','count','tamanio'));
          //  $pdf->setPaper('A4', 'landscape');
          // $paper_size = array(0,0,900,660);
          // $pdf->setPaper($paper_size);
         $pdf->setPaper("A4", "portrait");
      //     // return $pdf->download('cv-interno.pdf');
          return $pdf->stream();

    }

    public function trazabilidad($id)
    {
        ini_set('max_execution_time', 1000);
        return Excel::download(new TrazabilidadExport($id), 'ReporteTrazabilidad .xlsx');
        // return (new QuincenaExport)->download('invoices.xlsx');
    }


}
