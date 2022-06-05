<?php

namespace App\Http\Controllers;

date_default_timezone_set('America/Mexico_City');

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Requisicion;
use App\Proyecto;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;
use File;
use Illuminate\Support\Facades\Storage;
use App\User;
use Mail;
use App\Http\Controllers\RequisicionPdfController;


class RequisicionController extends Controller
{
  /**
   * [Definición de las reglas para ser utilizadas en la actualizacion y la insercion]
   * @var [type]
   */
  protected $rules = array(
    //  'folio' => 'required|max:30',
    'area_solicita_id' => 'required',
    'fecha_solicitud' => 'required',
    'descripcion_uso' => 'required',
    'tipo_compra' => 'required',
    'descripcion_uso' => 'required|max:255',
    'proyecto_id' => 'required',
    'estado_id' => 'required',
    'solicita_empleado_id' => 'required',
    'autoriza_empleado_id' => 'required',
    'valida_empleado_id' => 'required',
  );


  /**
   * [index Busqueda especifica de las partidas correspondientes a la requisicion buscada por el id]
   * @param  Request $request [description]
   * @param  Int  $id      [description]
   * @return Response           [description]
   */
  public function index(Request $request, $id)
  {
    $arreglo = [];
    $requisiciones = DB::table('requisiciones')
      ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
      ->leftJoin('articulos as a', 'a.id', '=', 'pr.articulo_id')
      ->leftJoin('servicios as s', 's.id', '=', 'pr.servicio_id')
      ->select(
        'requisiciones.*',
        'a.nombre', //p
        'a.codigo', //p
        'a.descripcion', //p
        'a.marca',       //p
        's.nombre_servicio',
        's.unidad_medida',
        's.proveedor_marca',
        'pr.peso AS peso',
        'pr.cantidad AS cantidades',
        'pr.equivalente',
        'pr.fecha_requerido AS frequerido',
        'pr.condicion AS condicion',
        'pr.requisicione_id',
        'pr.cantidad_compra',
        'pr.cantidad_almacen',
        'pr.articulo_id',
        'pr.servicio_id',
        'pr.pda',
        'pr.comentario AS comentario_partida',
        'pr.produccion AS produccion',
        'a.nombre AS narticulo',
        'a.codigo AS carticulo',
        'a.descripcion AS darticulo',
        'a.unidad AS unidad_articulo'
      )
      ->where('pr.requisicione_id', '=', $id)
      ->where('pr.articulo_id', '!=', null)
      ->orderBy('requisiciones.id', 'ASC')
      ->get();

    foreach ($requisiciones as $key => $value)
    {

      $documentos = DB::table('partidarequisicion_documentos')
        ->leftJoin('documentos_proveedores AS DP', 'DP.id', '=', 'partidarequisicion_documentos.documento_id')
        ->select('partidarequisicion_documentos.*', 'DP.nombre', 'DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_art', '=', $value->articulo_id)
        ->where('partidarequisicion_documentos.partidarequisicione_req', '=', $value->requisicione_id)->get();

      $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id', $value->requisicione_id)
        ->where('pda', $value->pda)->where('articulo_servicio', '1')->where('articulo_servicio_id', $value->articulo_id)
        ->where('activo', '1')
        ->first();

      $arreglo[] = [
        'req' => $value,
        'doc' => $documentos,
        'correccion' => $comentarios,
      ];
    }
    return response()->json($arreglo);
  }

  /**
   * [store Inserta un nuevo registro en la BD]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function store(Request $request)
  {
    if (!$request->ajax()) return redirect('/');

    // Comprobar si el proyecto esta activo
    $proyecto = Proyecto::findOrFail($request->proyecto_id);
    if ($proyecto->condicion != 1)
    {
      return response()->json(array(
        'status' => false,
        'errors' => ['El proyecto no esta activo.']
      ));
    }

    if ($request->identificador == 1)
    {

      //obtiene el nombre del archivo y su extension
      $AdjuntoNE = $request->file('adjunto')->getClientOriginalName();
      //Obtiene el nombre del archivo
      $AdjuntoNombre = pathinfo($AdjuntoNE, PATHINFO_FILENAME);
      //obtiene la extension
      $AdjuntoExt = $request->file('adjunto')->getClientOriginalExtension();
      //nombre que se guarad en BD
      $AdjuntoStore = $AdjuntoNombre . '_' . uniqid() . '.' . $AdjuntoExt;
      //Subida del archivo al servidor ftp
      Storage::disk('local')->put('Archivos/' . $AdjuntoStore, fopen($request->file('adjunto'), 'r+'));

      $validator = Validator::make($request->all(), $this->rules);

      $requi = Requisicion::findOrFail($request->id);
      $requi->formato_requisiciones = $AdjuntoStore;
      $requi->save();
      return response()->json(array(
        'status' => true,
        'data' => $requi
      ));
    }
    else
    {
      if (!$request->ajax()) return redirect('/');
      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails())
      {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      $requi = new Requisicion();
      // Folio generado por el sistema
      $folio = Utilidades::getFolio('REQ', $request->proyecto_id);
      $request->merge(['folio' => $folio]);
      $requi->fill($request->all());
      Utilidades::auditar($requi, $requi->id);
      $requi->save();
      return response()->json(array(
        'status' => true,
        'data' => $requi
      ));
    }
  }

  /**
   * [update Actualia un registro de la tabla requisiciones en la BD]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function update(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $requi = Requisicion::findOrFail($request->id);
    $requi->fill($request->all());
    Utilidades::auditar($requi, $requi->id);
    $requi->save();
    return response()->json(array(
      'status' => true
    ));
  }

  /**
   * [Obtiene y descarga el archivo]
   * @param Request   $request [Objeto de datos del POST]
   * @return Response          []
   */

  public function descargarArchivo(Request $request)
  {
    if ($request->metodo == 1)
    {
      // Se obtiene el archivo del FTP serve
      $archivo = Utilidades::ftpSolucion($request->archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($request->archivo, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path() . '/app/descargas/' . $request->archivo);
    }

    if ($request->metodo == 0)
    {
      //elimina de la ruta local el archivo descargado
      Storage::disk('descarga')->delete($request->archivo);
      Storage::disk('local')->delete($request->archivo);
    }
  }

  /**
   * [edit Acutualiza el campo condicion de un registro especifico en la BD]
   * @param  Int $id [description]
   * @return Response     [description]
   */
  public function edit($id)
  {
    $requi = Requisicion::findOrFail($id);
    if ($requi->condicion == 0)
    {
      $requi->condicion = 1;
    }
    else
    {
      $requi->condicion = 0;
    }
    $requi->update();
    return $requi;
  }

  /**
   * [show Consulta en el modelo Requisicion todos los registros en el]
   * @param  Request $request [description]
   * @return Response           [description]
   */
  public function show($id)
  {
    $requisicion = Requisicion::orderBy('id', 'desc')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
      ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
      ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
      ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
      ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
      ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', 'p.proyecto_subcategorias_id')
      ->select(
        'requisiciones.*',
        'p.nombre AS nombrep',
        'p.nombre_corto as p_nombre_corto',
        DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_empleado_solicita"),
        DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"),
        DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombre_empleado_valida"),
        DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_empleado_recibe"),
        'ps.proyecto_categoria_id'
      )
      ->where('requisiciones.proyecto_id', '=', $id)
      ->where('requisiciones.estado_id', '!=', '9')->where('requisiciones.estado_id', '!=', '11')
      ->get();
    $arreglo = [];

    foreach ($requisicion as $key => $value)
    {

      $comentario = \App\IncidenciasRequisiciones::where('requisicion_id', $value->id)
        ->where('pda', '0')->where('articulo_servicio_id', '0')->where('activo', '1')->first();

      $arreglo[] = [
        'r' => $value,
        'c' => $comentario,
      ];
    }

    return response()->json($arreglo);
  }


    /**
     * [show Consulta en el modelo Requisicion todos los registros en el]
     * @param  Request $request [description]
     * @return Response           [description]
     */
    public function showNew($id)
    {
      $requisicion = Requisicion::orderBy('id', 'desc')
        ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
        ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
        ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
        ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
        ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
        ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', 'p.proyecto_subcategorias_id')
        ->select(
          'requisiciones.*',
          'p.nombre AS nombrep',
          'p.nombre_corto as p_nombre_corto',
          DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_empleado_solicita"),
          DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"),
          DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombre_empleado_valida"),
          DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_empleado_recibe"),
          'ps.proyecto_categoria_id'
        )
        ->where('requisiciones.proyecto_id', '=', $id)
        ->where('requisiciones.estado_id', '!=', '9')->where('requisiciones.estado_id', '!=', '11')
        ->get();
      $arreglo = [];

      foreach ($requisicion as $key => $value)
      {

        $comentario = \App\IncidenciasRequisiciones::where('requisicion_id', $value->id)
          ->where('pda', '0')->where('articulo_servicio_id', '0')->where('activo', '1')->first();

        $arreglo[] = [
          'requisicion' => $value,
          'comentario' => $comentario,
        ];
      }

      return response()->json($arreglo);
    }

  public function ducumentosrequeridos()
  {
    //  if (!$request->ajax()) return redirect('/');
    $documentos = DB::table('documentos_proveedores')->select('documentos_proveedores.*')->get();
    return response()->json($documentos);
  }
  public function ducumentosrequeridosb($id)
  {
    //if (!$request->ajax()) return redirect('/');
    $documentos = DB::table('documentos_proveedores')->select('documentos_proveedores.*')->where('documentos_proveedores.id', '=', $id)->get();
    return response()->json($documentos);
  }
  public function partidadocumentos($id)
  {
    $valores = explode("&", $id);
    $servicio = $valores[2];
    $articulo = $valores[0];

    if ($servicio != "null")
    {
      $documentos = DB::table('partidarequisicion_documentos')->select('partidarequisicion_documentos.*')
        ->where('partidarequisicion_documentos.partidarequisicione_serv', '=', $valores[2])
        ->where('partidarequisicion_documentos.partidarequisicione_req', '=', $valores[1])->get();
      return response()->json($documentos);
    }

    if ($articulo != "null")
    {
      $documentos = DB::table('partidarequisicion_documentos')->select('partidarequisicion_documentos.*')
        ->where('partidarequisicion_documentos.partidarequisicione_art', '=', $valores[0])
        ->where('partidarequisicion_documentos.partidarequisicione_req', '=', $valores[1])->get();
      return response()->json($documentos);
    }
  }

  /**
   * [activar description]
   * @param  Request $request [description]
   * @return [type]           [description]
   */
  public function activar(Request $request)
  {
    if (!$request->ajax()) return redirect('/');
    $requi = Requisicion::findOrFail($request->id);
    $requi->condicion = '1';
    $requi->save();
  }

  /**
   * [finalizarequisicion Acutualiza el campo estado_id de un registro en la DB simulando el cierre de una requisición pasando a revision]
   * @param  Int $id [description]
   * @return Response     [description]
   */
  public function finalizarequisicion($id)
  {
    try
    {


      $id_u = Auth::id();
      $users = DB::table('users')->select('users.*')->where('users.id', '=', $id_u)->first();
      $requisicion = Requisicion::where('id', '=', $id)->first();
      $proyecto = DB::table('proyectos')->where('proyectos.id', $requisicion->proyecto_id)
        ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', '=', 'proyectos.proyecto_subcategorias_id')
        ->join('proyecto_categorias AS pc', 'pc.id', '=', 'ps.proyecto_categoria_id')
        ->select('pc.nombre')
        ->first();
      $this->quitar_comentarios($requisicion->id);

      $mensaje = "Aviso revision de requisición";

      // if ($proyecto->nombre === 'Proyectos')
      // {
      //
      //   $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
      //     // ->where('empleados.id',$requisicion->valida_empleado_id)
      //     ->whereIn('empleado_id', ['8'])
      //     // ->where('empleado_id','8')
      //     ->select('empleados.*', 'U.email')->get();
      //
      //   foreach ($empleado_valida as $key => $value)
      //   {
      //     $this->enviarCorreoPR($value, $mensaje, $requisicion);
      //   }
      // }
      // else
      // {

        //Envio de correo inicio
        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->where('empleados.id', $requisicion->valida_empleado_id)->select('empleados.*', 'U.email')->first();
        $this->enviarCorreoPR($empleado_valida, $mensaje, $requisicion);
      // }





      // Envio de correo fin

      // $requisicion_partida_produccion = \App\PartidaRe::where('requisicione_id','=',$requisicion->id)
      // ->where('produccion','=','1')->get();
      // $contador_produccion = count($requisicion_partida_produccion);
      //
      // $requisicion_partida_art_serv = \App\PartidaRe::where('requisicione_id','=',$requisicion->id)
      // ->where('articulo_id','!=','null')->get();
      // $contador_art_serv = count($requisicion_partida_art_serv);
      //
      //
      // if ($contador_produccion > 0) {
      //   if ($contador_art_serv > 0) {
      //     $requisicion->estado_id = 8;
      //     $requisicion->save();
      //     return response()->json(array('status' => true));
      //
      //   }elseif ($contador_art_serv == 0) {
      //     $requisicion->estado_id = 5;
      //     $this->completarRequisicion($requisicion->id);
      //     $requisicion->almacen_empleado_id = $users->empleado_id;
      //     $requisicion->save();
      //     return response()->json(array('status' => true));
      //
      //   }
      // }else {
      //   if ($contador_art_serv > 0) {
      //     $requisicion->estado_id = 3;
      //     $requisicion->save();
      //     return response()->json(array('status' => true));
      //
      //   }elseif ($contador_art_serv == 0) {
      //     $requisicion->estado_id = 5;
      //     $this->completarRequisicion($requisicion->id);
      //     $requisicion->almacen_empleado_id = $users->empleado_id;
      //     $requisicion->save();
      //     return response()->json(array('status' => true));
      //
      //   }
      // }
      //se cambia el estado de finalizacion de requisicion a 6 para que pase directo a validacion a compras, el estado por defaul es 1
      $requisicion->estado_id = 1;
      Utilidades::auditar($requisicion, $requisicion->id);
      $requisicion->save();
      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  /**
   * [requisicionesporautorizar Busca en las requisiciones con el id de empleado autentificado]
   * @return Response [description]
   */
  public function requisicionesporautorizar()
  {
    $id = Auth::user();
    // if ($id->empleado_id == 11) {
    //   $requisiciones = DB::table('requisiciones')
    //   ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    //   ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
    //   ->join('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
    //   ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    //   ->select('requisiciones.*','p.nombre_corto AS nombrep',DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"))
    //   ->where('requisiciones.estado_id','=','2')
    //   ->whereIn('pc.nombre',['Proyectos'])
    //   ->orderBy('requisiciones.fecha_solicitud','DESC')
    //   ->get();
    // }else {
    $requisiciones = DB::table('requisiciones')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', '=', 'p.proyecto_subcategorias_id')
      ->join('proyecto_categorias AS pc', 'pc.id', '=', 'ps.proyecto_categoria_id')
      ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
      ->select('requisiciones.*', 'p.nombre_corto AS nombrep', DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"))
      ->where('requisiciones.autoriza_empleado_id', $id->empleado_id)
      ->where('requisiciones.estado_id', '=', '2')
      // ->whereNotIn('pc.nombre',['Proyectos'])
      // ->orderBy('id','DESC')
      ->orderBy('requisiciones.id', 'desc')
      ->get();
    // }


    return response()->json($requisiciones);
  }
  /**
   * [requisicionesporautorizar Busca en las requisiciones con el id de empleado autentificado]
   * @return Response [description]
   */
  public function requisicionesporvalidar()
  {
    $id = Auth::user();
    $users = DB::table('users')->select('users.*')->where('users.id', '=', $id)->first();
    $requisiciones = [];

    if ($id->empleado_id == 8)
    {
      $requisiciones = DB::table('requisiciones')
        ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', '=', 'p.proyecto_subcategorias_id')
        ->join('proyecto_categorias AS pc', 'pc.id', '=', 'ps.proyecto_categoria_id')
        ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
        ->select('requisiciones.*', 'p.nombre_corto AS nombrep', DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"))
        // ->whereIn('pc.nombre',['Proyectos'])
        ->orWhere('requisiciones.valida_empleado_id', $id->empleado_id)
        ->where('requisiciones.estado_id', '=', '1')
        // ->where('requisiciones.estado_id','!=','11')
        ->orderBy('id', 'DESC')
        ->get();
      return response()->json($requisiciones);
    }
    else
    {
      $requisiciones = DB::table('requisiciones')
        ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', '=', 'p.proyecto_subcategorias_id')
        ->join('proyecto_categorias AS pc', 'pc.id', '=', 'ps.proyecto_categoria_id')
        ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
        ->select('requisiciones.*', 'p.nombre_corto AS nombrep', DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"))
        ->where('requisiciones.valida_empleado_id', $id->empleado_id)
        ->where('requisiciones.estado_id', '=', '1')
        // ->whereNotIn('pc.nombre', ['Proyectos'])
        ->orderBy('id', 'DESC')
        ->get();
    }

    return response()->json($requisiciones);
  }

  /**
   * [autorizarequisicionproyectos Actualiza el estado_id de un registro simulando la autorizacion o no]
   * @param  Request $request [description]
   * @return Response         [description]
   */
  /*
  * 1.- CERRAR REQUISICION ENVIAR PARA REVISAR
  *  (controlador)  RequisicionController => (funcion) finalizarequisicion
  * 2.- ENVIAR REQUISICION A CALIDAD ESTANDO A REVISION
  *  (controlador)  RequisicionController => (funcion) autorizarequisicionproyectos => (estado) $request->estado == 8
  * 3.- ENVIAR REQUISICION A AUTORIZACION ESTANDO EN CALIDAD
  *  (controlador) RequisicionController => (funcion) autorizarequisicionproyectos => (estado) $request->estado == 1
  * 4.- ENVIAR REQUISICION A ALMACEN O COMPRAS DEPENDIENDO SI LA REQUISICION TIENE ARTICULOS O NO
  *  (controlador) RequisicionController => (funcion) autorizarequisicionproyectos => (estado) $request->estado == 2
  *      (contador) => $contador > 0 se tienen articulos en la requisicion y pasan a revision en almacen
  *      (contador) => $contador == 0 no se tienen articulos en la requisicion solo servicio y pasa directo a recibir en compras
  * 5.- ENVIAR REQUISICION A COMPRAS PARA RECIBIR
  *  (controlador) RequisicionController => (funcion) autorizarequisicionproyectos => (estado) $request->estado == 3
  * 6.- RECIBIR REQUISICION EN COMPRAR PARA PODER CONSUMIR
  *  (controlador) RequisicionController => (funcion) autorizarequisicionproyectos => (estado) $request->estado == 4
  * 7.- NO AUTORIZAR REQUISICION APLICABLE EN CUALQUIER DASHBOARD
  *  (controlador) RequisicionController => funcion autorizarequisicionproyectos = (estado) $request->estado == 0
  */
  public function autorizarequisicionproyectos(Request $request)
  {
    try
    {
      $contador = 0;
      $requisiciones = Requisicion::where('id', '=', $request->id)->first();
      $id = Auth::id();
      $users = DB::table('users')->select('users.*')->where('users.id', '=', $id)->first();
      $proyecto = DB::table('proyectos')->where('proyectos.id', $requisiciones->proyecto_id)
        ->leftJoin('proyecto_subcategorias AS ps', 'ps.id', '=', 'proyectos.proyecto_subcategorias_id')
        ->join('proyecto_categorias AS pc', 'pc.id', '=', 'ps.proyecto_categoria_id')
        ->select('pc.nombre AS nombre')
        ->first();

      /**************************************************************************/
      //1.-REQUISICION ENVIADA A CALIDAD PASO 3.- DESPUES DE VALIDAR EN DASHBOARD
      if ($request->estado == 8)
      { //Validadado para ver en calidad
        /*
        //En este fragmento se debe cambiar el correo por el que valida calidad
        $mensaje ="Aviso revision de requisición calidad";
        $empleado_valida = \App\Empleado::join('contacto_empleados AS CE', 'empleados.id', '=', 'CE.empleado_id')
        ->where('empleados.id',$requisiciones->valida_empleado_id)->select('empleados.*','CE.correo_electronico')->first();
        Utilidades::enviarCorreoPR($empleado_valida,$mensaje);
        */
        ///////////////////////
        // $requisicion_partida = \App\PartidaRe::where('requisicione_id','=',$requisiciones->id)
        // ->where('produccion','=','1')->get();
        // $contador = count($requisicion_partida);
        // if ($contador > 0) {
        //   $requisiciones->estado_id = 8;
        //   $requisiciones->save();
        // }else {
        //   //Envio de correo inicio
        //   $mensaje ="Aviso autorización de requisición";
        //   $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
        //   ->where('empleados.id',$requisiciones->autoriza_empleado_id)->select('empleados.*','U.email')->first();
        //   $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);
        //   // Envio de correo fin
        //
        //   $requisiciones->estado_id = 2;
        //   $requisiciones->save();
        // }
        //////////////////////////////
        $this->quitar_comentarios($requisiciones->id);
        if ($proyecto->nombre === 'Proyectos')
        {

          //Envio de correo inicio
          $mensaje = "Aviso autorización de requisición";
          $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
            ->where('empleados.id', '11')->select('empleados.*', 'U.email')->first();
          $this->completarRequisicion($requisiciones->id);
          $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);
          // Envio de correo fin
          $requisiciones->estado_id = 2;
          $requisiciones->almacen_empleado_id = $users->empleado_id;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
        else
        {
          // code...
          //Envio de correo inicio
          $mensaje = "Aviso autorización de requisición";
          $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
            ->where('empleados.id', $requisiciones->autoriza_empleado_id)
            ->select('empleados.*', 'U.email')->first();
          $this->completarRequisicion($requisiciones->id);
          $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);
          // Envio de correo fin
          $requisiciones->estado_id = 2;
          $requisiciones->almacen_empleado_id = $users->empleado_id;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
      }
      /**************************************************************************/
      //2.- REQUISICION ENVIADA A AUTORIZAR PASO 4.- DESPUES DE VALIDAR CALIDAD DASHBOARD
      if ($request->estado == 1)
      { //Validaddo para ver en autorizar o pasar directo si es equivalente
        /*
        $mensaje ="Aviso autorización de requisición";
        $empleado_valida = \App\Empleado::join('contacto_empleados AS CE', 'empleados.id', '=', 'CE.empleado_id')
        ->where('empleados.id',$requisiciones->autoriza_empleado_id)->select('empleados.*','CE.correo_electronico')->first();
        Utilidades::enviarCorreoPR($empleado_valida,$mensaje);
        */
        if ($requisiciones->condicion == 2)
        {
          $requisiciones->estado_id = 3;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
        else
        {
          $requisiciones->estado_id = 3;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
      }
      /**************************************************************************/
      if ($request->estado == 2)
      { //Autorizado prar ver en alamcen
        $requisicion_partida = \App\PartidaRe::where('requisicione_id', '=', $requisiciones->id)
          ->where('articulo_id', '!=', 'null')->get();
        $contador = count($requisicion_partida);

        $requisiciones->fecha_autorizada = date('Y-m-d');

        if ($contador > 0)
        {

          $mensaje = "Aviso revision de articulos de requisición";
          $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
            ->whereIn('U.email', ['almacen.tehuacan@conserflow.com', 'jaime.martinez@conserflow.com'])
            ->select('empleados.*', 'U.email')->get();
          foreach ($empleado_valida as $al => $a)
          {
            $this->enviarCorreoPR($a, $mensaje, $requisiciones);
          }

          $requisiciones->estado_id = 3;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
        elseif ($contador == 0)
        {
          //Envio de correo inicio
          // $mensaje ="Aviso solicitud compra de requisición";
          if ($proyecto->nombre === 'Proyectos')
          {
            //Envio de correo inicio
            $mensaje = "Aviso autorización de requisición";
            $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
              ->where('empleados.id', $requisiciones->autoriza_empleado_id)->select('empleados.*', 'U.email')->first();
            $this->completarRequisicion($requisiciones->id);
            $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);
            // Envio de correo fin
            $requisiciones->estado_id = 2;
            $requisiciones->almacen_empleado_id = $users->empleado_id;
            Utilidades::auditar($requisiciones, $requisiciones->id);
            $requisiciones->save();
          }
          else
          {
            //Envio de correo inicio
            $mensaje = "Aviso autorización de requisición";
            $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
              ->where('empleados.id', $requisiciones->autoriza_empleado_id)
              ->select('empleados.*', 'U.email')->first();
            $this->completarRequisicion($requisiciones->id);
            $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);
            // Envio de correo fin
            $requisiciones->estado_id = 2;
            $requisiciones->almacen_empleado_id = $users->empleado_id;
            Utilidades::auditar($requisiciones, $requisiciones->id);
            $requisiciones->save();
          }
          // $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          // ->where('U.email','romerovelascogregorio@gmail.com')->select('empleados.*','U.email')->first();
          // $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);
          // Envio de correo fin
          $requisiciones->estado_id = 2;
          $this->completarRequisicion($requisiciones->id);
          $requisiciones->almacen_empleado_id = $users->empleado_id;
          Utilidades::auditar($requisiciones, $requisiciones->id);
          $requisiciones->save();
        }
      }
      /**************************************************************************/
      if ($request->estado == 3)
      { //Enviado a compras
        $this->quitar_comentarios($requisiciones->id);

        //Envio de correo inicio
        $mensaje = "Aviso solicitud compra de requisición";

        if ($proyecto->nombre === 'Proyectos')
        {
          $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
            ->whereIn('U.email', ['joel.machorro@conserflow.com', 'alejandro.machorro@conserflow.com'])
            // ->where('U.email','romerovelascogregorio@gmail.com')
            // ->orWhere('U.email','gregorio.romero@conserflow.com')
            ->select('empleados.*', 'U.email')->get();
          foreach ($empleado_valida as $key => $vev)
          {
            $this->enviarCorreoPR($vev, $mensaje, $requisiciones);
          }
        }
        else
        {
          $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
            ->whereIn('U.email', ['joel.machorro@conserflow.com', 'alejandro.machorro@conserflow.com', 'cesar.abad@conserflow.com'])
            // ->where('U.email','romerovelascogregorio@gmail.com')
            ->select('empleados.*', 'U.email')->get();
          foreach ($empleado_valida as $keys => $vevs)
          {
            $this->enviarCorreoPR($vevs, $mensaje, $requisiciones);
            // $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);
          }
        }


        // Envio de correo fin
        $requisiciones->estado_id = 6;
        $requisiciones->fecha_completada = date('Y-m-d');
        // $requisiciones->almacen_empleado_id = $users->empleado_id;
        $this->completarRequisicion($requisiciones->id);
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }
      /**************************************************************************/
      if ($request->estado == 0)
      { //no autorizado
        $requisiciones->estado_id = 4;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }
      /**************************************************************************/
      if ($request->estado == 4)
      { //recibido por compras
        //Envio de correo inicio
        $mensaje = "Requisición recibida en Compras";

        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->where('empleados.id', $requisiciones->solicita_empleado_id)
          ->select('empleados.*', 'U.email')->first();

        $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);
        // $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
        // ->where('U.email','romerovelascogregorio@gmail.com')->select('empleados.*','U.email')->first();
        // $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);
        // Envio de correo fin
        $requisiciones->estado_id = 5;
        $requisiciones->fecha_recibida = date('Y-m-d');
        $requisiciones->recibe_empleado_id = $users->empleado_id;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }

      if ($request->estado == 10)
      {
        $mensaje = "Requisicion rechazada por compras REVISAR PARTIDAS";
        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->whereIn('U.email', ['almacen.tehuacan@conserflow.com', 'jaime.martinez@conserflow.com'])
          ->select('empleados.*', 'U.email')->get();

        foreach ($empleado_valida as $bm => $b)
        {
          $this->enviarCorreoPR($b, $mensaje, $requisiciones);
        }

        // $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);

        $requisiciones->estado_id = 3;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }

      if ($request->estado == 11)
      {
        $mensaje = "Requisicion rechazada por compras REVISAR PARTIDAS";
        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->where('empleados.id', $requisiciones->solicita_empleado_id)
          ->select('empleados.*', 'U.email')->first();

        $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);

        $requisiciones->estado_id = 4;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }

      if ($request->estado == 12)
      {
        $mensaje = "Requisicion rechazada REVISAR PARTIDAS";
        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->whereIn('U.email', ['almacen.tehuacan@conserflow.com', 'jaime.martinez@conserflow.com'])
          ->select('empleados.*', 'U.email')->get();
        foreach ($empleado_valida as $cs => $c)
        {
          $this->enviarCorreoPR($c, $mensaje, $requisiciones);
        }
        // $this->enviarCorreoPR($empleado_valida,$mensaje,$requisiciones);

        $requisiciones->estado_id = 3;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }

      if ($request->estado == 14)
      {
        $mensaje = "Requisicion rechazada REVISAR PARTIDAS";
        $empleado_valida = \App\Empleado::join('users AS U', 'empleados.id', '=', 'U.empleado_id')
          ->where('empleados.id', $requisiciones->solicita_empleado_id)
          ->select('empleados.*', 'U.email')->first();

        $this->enviarCorreoPR($empleado_valida, $mensaje, $requisiciones);

        $requisiciones->estado_id = 4;
        Utilidades::auditar($requisiciones, $requisiciones->id);
        $requisiciones->save();
      }

      return response()->json($request->estado);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  /**
   * [guardarcantidadalmacen funcionn que aparta de los lotes correspondientes la cantidad definada por el Usuario
   * para se agregadas a la requisición]
   * @param  Request $request [description]
   * @return Response          [description]
   */
  public function guardarcantidadalmacen(Request $request)
  {
    try
    {


      $cantidad_compra = 0;

      $lote_almacen = \App\LoteAlmacen::where('id', '=', $request->lote_almacen_id)->first();
      $lote_almacen->cantidad = floatval($lote_almacen->cantidad) - floatval($request->cantidad);
      // return response()->json($lote_almacen);
      $lote_almacen->save();
      //
      $lote_temporal = \App\LoteTemporal::where('lote_almacen_id', '=', $request->lote_almacen_id)->first();
      if ($lote_temporal != null)
      {
        $lote_temporal->requisicion_id =  $request->requisicion_id;
        $lote_temporal->articulo_id = $request->articulo_id;
        $lote_temporal->lote_almacen_id = $request->lote_almacen_id;
        $lote_temporal->cantidad =  $lote_temporal->cantidad + floatval($request->cantidad);
        $lote_temporal->comentario = 'Apartado por requisicion';
        $lote_temporal->save();
      }
      else
      {
        $lote_temporal_new = new \App\LoteTemporal();
        $lote_temporal_new->requisicion_id =  $request->requisicion_id;
        $lote_temporal_new->articulo_id = $request->articulo_id;
        $lote_temporal_new->lote_almacen_id = $request->lote_almacen_id;
        $lote_temporal_new->cantidad =  floatval($request->cantidad);
        $lote_temporal_new->comentario = 'Apartado por requisicion';
        $lote_temporal_new->save();
      }
      //

      $lote_temporal_suma = DB::table('lote_temporal')
        ->select(DB::Raw('SUM(cantidad) AS cantidad_almacen'))
        ->where('requisicion_id', '=', $request->requisicion_id)
        ->where('articulo_id', '=', $request->articulo_id)->first();

      $partida_requisiciones = \App\PartidaRe::where('requisicione_id', '=', $request->requisicion_id)
        ->where('articulo_id', '=', $request->articulo_id)->first();
      $cantidad_compra = $partida_requisiciones->cantidad - $lote_temporal_suma->cantidad_almacen;
      //
      DB::table('partidas_requisiciones')->where([
        ['requisicione_id', '=', $request->requisicion_id],
        ['articulo_id', '=', $request->articulo_id],
      ])->update(['cantidad_compra' => $cantidad_compra, 'cantidad_almacen' => $lote_temporal_suma->cantidad_almacen]);


      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function completarRequisicion($id)
  {
    try
    {
      $requisiciones = Requisicion::where('id', '=', $id)->first();

      $partida_requisiciones = \App\PartidaRe::where('requisicione_id', '=', $id)->get();
      for ($i = 0; $i < count($partida_requisiciones); $i++)
      {
        if ($partida_requisiciones[$i]->cantidad_compra == null)
        {
          DB::table('partidas_requisiciones')->where([
            ['requisicione_id', '=', $partida_requisiciones[$i]->requisicione_id],
            ['pda', '=', $partida_requisiciones[$i]->pda],

          ])->update(['cantidad_compra' => $partida_requisiciones[$i]->cantidad]);
        }
        if ($partida_requisiciones[$i]->cantidad_almacen == null)
        {
          DB::table('partidas_requisiciones')->where([
            ['requisicione_id', '=', $partida_requisiciones[$i]->requisicione_id],
            ['pda', '=', $partida_requisiciones[$i]->pda],

          ])->update(['cantidad_almacen' => 0]);
        }
      }
      return true;
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  /**
   * [obtiene lote temporal description]
   * @param  String $id [description]
   * @return Response     [description]
   */
  public function obtenerlotetemporal($id)
  {
    $valores = explode('&', $id);
    $lote_temporal = DB::table('lote_temporal')
      ->leftJoin('lote_almacen AS LA', 'LA.id', '=', 'lote_temporal.lote_almacen_id')
      ->leftJoin('lotes AS L', 'L.id', '=', 'LA.lote_id')
      ->leftJoin('stocks AS S', 'S.id', '=', 'LA.stocke_id')
      ->select('lote_temporal.*', 'S.nombre', 'L.nombre AS lote_nombre')
      ->where('lote_temporal.requisicion_id', '=', $valores[1])
      ->where('lote_temporal.articulo_id', '=', $valores[0])->get();
    return response()->json($lote_temporal);
    // code...
  }

  public function requisicionalmacenquitar(Request $request)
  {
    $cantidad = $request->cantidad;
    $cantidad_almacen = $request->cantidad_almacen;
    $ids = $request->ids;
    for ($i = 0; $i < count($request->cantidad); $i++)
    {
      $lote_temporal = \App\LoteTemporal::where('id', '=', $ids[0][$i]['id'])->first();
      $lote_temporal->cantidad = $lote_temporal->cantidad - ($cantidad[$i] == 0 ? $cantidad_almacen[$i] : $cantidad[$i]);
      $lote_temporal->save();

      $partidaRe = \App\PartidaRe::where('requisicione_id', '=', $lote_temporal->requisicion_id)
        ->where('articulo_id', '=', $lote_temporal->articulo_id)->first();
      $cantidad_compra = $partidaRe->cantidad_compra + ($cantidad[$i] == 0 ? $cantidad_almacen[$i] : $cantidad[$i]);
      $cantidad_almacen = $partidaRe->cantidad_almacen - ($cantidad[$i] == 0 ? $cantidad_almacen[$i] : $cantidad[$i]);

      DB::table('partidas_requisiciones')->where([
        ['requisicione_id', '=', $lote_temporal->requisicion_id],
        ['articulo_id', '=', $lote_temporal->articulo_id],
      ])->update(['cantidad_compra' => $cantidad_compra]);

      DB::table('partidas_requisiciones')->where([
        ['requisicione_id', '=', $lote_temporal->requisicion_id],
        ['articulo_id', '=', $lote_temporal->articulo_id],
      ])->update(['cantidad_almacen' => $cantidad_almacen]);

      $lote_almacen = \App\LoteAlmacen::where('id', '=', $lote_temporal->lote_almacen_id)->first();
      $lote_almacen->cantidad = $lote_almacen->cantidad + ($request->cantidad[$i] == 0 ? $request->cantidad_almacen[$i] : $request->cantidad[$i]);
      $lote_almacen->save();
    }
    return response()->json(array('status' => true));
  }


  public function folio()
  {
    $folio = Utilidades::getFolio('OC-CSCT', 2);
    echo $folio;
  }

  public function requisicionescalidadver($value = '')
  {
    $requisiciones = DB::table('requisiciones')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
      ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
      ->select('requisiciones.*', 'p.nombre_corto AS nombrep', DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"))
      // ->where('autoriza_empleado_id','=',$users->empleado_id)
      ->where('requisiciones.estado_id', '=', '8')
      ->get();
    return response()->json($requisiciones);
  }

  public function vercantidadpartidas($id)
  {
    $requisicion_p = \App\PartidaRe::where('requisicione_id', '=', $id)->get();
    return response()->json($requisicion_p);
  }

  public function requisicionespendientes()
  {
    $arreglo = [];
    $requisicion = DB::table('requisiciones')
      ->join('proyectos AS P', 'P.id', '=', 'requisiciones.proyecto_id')
      ->join('empleados AS ES', 'ES.id', '=', 'requisiciones.solicita_empleado_id')
      ->select('requisiciones.*', 'P.nombre_corto AS nombrep', DB::raw("CONCAT(ES.nombre,' ',ES.ap_paterno,' ',ES.ap_materno) AS nombre_solicita"))
      ->where('requisiciones.estado_id', '=', '5')->orWhere('requisiciones.estado_id', '=', '9')->get();

    foreach ($requisicion as $key => $value)
    {
      $partida_re = DB::table('partidas_requisiciones')->select(DB::raw("COUNT(requisicione_id) AS c"))->where('condicion', '=', '1')
        ->where('requisicione_id', '=', $value->id)->first();
      if ($partida_re->c > 0)
      {
        $arreglo[] = [
          'arreglo' => $value,
          'partida_re' => $partida_re,
        ];
      }
    }

    return response()->json($arreglo);
  }
  /**
   * [Consulta las partidas de requisiciones pendientes]
   * @param Request   $request [Objeto de datos del POST]
   * @return Response          [json]
   */
  public function partidasrequisicionespendientes($id)
  {
    $data_art = DB::table('partidas_requisiciones')
      ->leftJoin('articulos AS a', 'a.id', '=', 'partidas_requisiciones.articulo_id')
      ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'r.proyecto_id')
      ->select(
        'partidas_requisiciones.*',
        'a.id AS ida',
        'r.id AS rid',
        'a.descripcion AS descripciona',
        'p.nombre AS proyecton',
        'p.id AS proyectoi',
        'a.marca',
        'r.folio AS rf',
        'r.fecha_solicitud AS rfs'
      )
      ->where('partidas_requisiciones.requisicione_id', '=', $id)
      ->where('partidas_requisiciones.condicion', '=', '1')
      ->where('partidas_requisiciones.articulo_id', '!=', 'null')
      ->get()->toArray();
    $data_serv = DB::table('partidas_requisiciones')
      ->leftJoin('servicios AS s', 's.id', '=', 'partidas_requisiciones.servicio_id')
      ->leftJoin('requisiciones AS r', 'r.id', '=', 'partidas_requisiciones.requisicione_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'r.proyecto_id')
      ->select(
        'partidas_requisiciones.*',
        's.id AS ida',
        'r.id AS rid',
        DB::raw("CONCAT(s.nombre_servicio,' ',s.proveedor_marca) AS descripciona"),
        'p.nombre AS proyecton',
        'p.id AS proyectoi',
        's.proveedor_marca AS marca',
        'r.folio AS rf',
        'r.fecha_solicitud AS rfs'
      )
      ->where('partidas_requisiciones.requisicione_id', '=', $id)
      ->where('partidas_requisiciones.condicion', '=', '1')
      ->where('partidas_requisiciones.servicio_id', '!=', 'null')
      ->get()->toArray();
    $partidas = array_merge($data_art, $data_serv);
    return response()->json($partidas);
  }
  /**
   * [Cancela las partidas de la requisicion]
   * @param Request   $request [Objeto de datos del POST]
   * @return Response          [Array con estatus true]
   */

  public function cancelarpartidasrequisiciones(Request $request)
  {
    $p = \App\PartidaRe::where('requisicione_id', '=', $request->requisicione_id)->where('pda', '=', $request->pda)->first();
    $partidas = DB::table('partidas_requisiciones')->where('requisicione_id', '=', $request->requisicione_id)
      ->where('pda', '=', $request->pda)->update([
        'condicion' => '2',
        'comentario' => $p->comentario . ' ' . '(cancelado)',
      ]);

    return response()->json(array('status' => true));
  }

  public function enviarCorreoPR($empleado_valida, $mensaje, $requisicion_)
  {
    try
    {



      $empleado_solicita = $requisicion_->solicita_empleado_id;


      if (isset($empleado_valida) == true)
      {
        $partida_a = DB::table('partidas_requisiciones')
          ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_requisiciones.articulo_id')
          ->select('partidas_requisiciones.*', 'A.id AS articulo_id', 'A.descripcion AS articulo_descripcion')
          ->where('partidas_requisiciones.requisicione_id', '=', $requisicion_->id)
          ->where('partidas_requisiciones.articulo_id', '!=', 'null')
          ->get()->toArray();
        $partida_s = DB::table('partidas_requisiciones')
          ->leftJoin('servicios AS S', 'S.id', '=', 'partidas_requisiciones.servicio_id')
          ->select('partidas_requisiciones.*', 'S.id AS articulo_id', 'S.nombre_servicio AS articulo_descripcion')
          ->where('partidas_requisiciones.requisicione_id', '=', $requisicion_->id)
          ->where('partidas_requisiciones.servicio_id', '!=', 'null')
          ->get()->toArray();

        $partida = array_merge($partida_a, $partida_s);



        $emmpleado_solicita = \App\Empleado::where('id', $empleado_solicita)->first();

        $hoy = date("Y-m-d");

        $data = [
          'nombre' => $empleado_valida->nombre . ' ' . $empleado_valida->ap_paterno . ' ' . $empleado_valida->ap_materno,
          'correo_electronico' => $empleado_valida->email,
          'fecha' => $hoy,
          'folio' => $requisicion_->folio,
          'mensaje' => $mensaje,
          'empleado_solicita' => $emmpleado_solicita->nombre . ' ' . $emmpleado_solicita->ap_paterno . ' ' . $emmpleado_solicita->ap_materno,
          'partidas' => $partida,
        ];
        $crear_pdf_requi = new RequisicionPdfController();
        $result_c_r = $crear_pdf_requi->pdfCorreo($requisicion_->id);

        $requisicion = $result_c_r['requisicion'];
        $partidas = $result_c_r['partidas'];
        $fechafinal = $result_c_r['fechafinal'];
        $fechaSolicitud = $result_c_r['fechaSolicitud'];
        $fechaAutorizada = $result_c_r['fechaAutorizada'];
        $fechaCompletada = $result_c_r['fechaCompletada'];
        $fechaRecibida = $result_c_r['fechaRecibida'];
        $partidascount = $result_c_r['partidascount'];
        $count = $result_c_r['count'];
        $valora = $result_c_r['valora'];
        $nombre_corto_proyecto = $result_c_r['nombre_corto_proyecto'];
        $numero_pedido = $result_c_r['numero_pedido'];

        $pdf = PDF::loadView('pdf.requisicionew', compact(
          'nombre_corto_proyecto',
          'numero_pedido',
          'requisicion',
          'partidas',
          'fechafinal',
          'fechaSolicitud',
          'fechaAutorizada',
          'fechaCompletada',
          'fechaRecibida',
          'partidascount',
          'count',
          'valora'
        ))->setPaper('letter', 'portrait');

//        $pdf->setPaper('letter', 'portrait');

        Mail::send('emails.procesorequisicion', $data, function ($message) use ($data, $pdf, $requisicion_, $empleado_valida, $mensaje)
        {
          $core = $empleado_valida->email;
          $message->to($core, $mensaje)
            ->subject($mensaje);
          $message->from('webmaster@conserflow.com', 'Conserflow');
          $message->attach(public_path().'');
          // $message->attachData($pdf->output(), $requisicion_->folio . '.pdf');
          //
          //  ->attach(public_path('/images').'/demo.jpg', [
          //         'as' => 'demo.jpg',
          //         'mime' => 'image/jpeg',
          // ]);

        });
      }
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function guardarCA(Request $request)
  {
    try
    {
      DB::table('partidas_requisiciones')->where([
        ['requisicione_id', '=', $request->requisicion],
        ['articulo_id', '=', $request->articulo_id],
        ['pda', $request->pda],
      ])->update(['cantidad_compra' => ($request->cantidad_i - $request->cantidad), 'cantidad_almacen' => $request->cantidad]);

      $requi = Requisicion::where('id', $request->requisicion)->first();
      $requi->pendiente = 1;
      $requi->save();

      return response()->json(array('status' => true));
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

    // code...
  }

  public function quitar_comentarios($requisicion_id)
  {
    try
    {

      $cambiar = DB::table('incidencias_requisiciones')
        ->where('requisicion_id', $requisicion_id)
        ->where('activo', '1')
        ->update(['activo' => 0]);
      return true;
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }
  }

  public function comentarioGral(Request $request)
  {
    try
    {

      $icg = new \App\IncidenciasRequisiciones();
      $icg->requisicion_id = $request->id;
      $icg->pda = 0;
      $icg->articulo_servicio = 0;
      $icg->articulo_servicio_id = 0;
      $icg->comentario = $request->data;
      $icg->save();

      return response()->json(['status' => true]);
    }
    catch (\Throwable $e)
    {
      Utilidades::errors($e);
    }


    // code...
  }

  public function cambiarestado(Request $request)
  {
    $requi = Requisicion::find($request->id);
    $requi->estado_id = $request->estado_id;
    $requi->save();
    return response()->json(["status" => true]);
  }
}
