<?php

namespace App\Http\Controllers;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use App\PagosNoRecurrentes;
use App\Compras;
use App\requisicionhasordencompras;
use App\User;
use Mail;


class PagosNoRecurrentesController extends Controller
{

  /**
   * [Consulta en la BD los registros de la tabla pagos_no_recurrentes y sus tablas
   * relacionadas proveedores, ordenes_compras, condicion_pago y proyectos]
   * @param  Request  $request [Url del GET]
   * @return Response          [Array en formato JSON]
   */
  public function index(Request $request)
  {

      $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
      ->leftJoin('proveedores', 'proveedores.id', '=', 'pagos_no_recurrentes.proveedor_id')
      ->leftJoin('ordenes_compras', 'ordenes_compras.id', '=', 'pagos_no_recurrentes.ordenes_comp_id')
      ->leftJoin('condicion_pago','condicion_pago.id','=','ordenes_compras.condicion_pago_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'pagos_no_recurrentes.proyecto_id')
      ->select('pagos_no_recurrentes.*','proveedores.limite_credito as ProvLimiteCredito','proveedores.dia_credito as ProvDiasCredito',
      'proveedores.banco_transferencia as bancos','proveedores.numero_cuenta as numcuenta','proveedores.clabe as clabe',
      'proveedores.nombre as NombreProveedor','proveedores.razon_social','ordenes_compras.fecha_orden','ordenes_compras.folio as FolioOrdendeCompra','proyectos.nombre_corto as NombreProyecto',
      'proyectos.ciudad as CiudadProyecto','condicion_pago.nombre as condicion_pago_nombre', 'moneda')
      ->whereIn('pagos_no_recurrentes.condicion',[4,7])
      ->orderBy('ordenes_compras.fecha_orden','DESC')
      ->get();


      return response()->json($pagosNoRecurrentes);

  }

  public function pagoNoRecurrentepago($id)
  {
    $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
    ->leftJoin('proveedores', 'proveedores.id', '=', 'pagos_no_recurrentes.proveedor_id')
    ->leftJoin('ordenes_compras', 'ordenes_compras.id', '=', 'pagos_no_recurrentes.ordenes_comp_id')
    ->leftJoin('condicion_pago','condicion_pago.id','=','ordenes_compras.condicion_pago_id')
    ->leftJoin('proyectos', 'proyectos.id', '=', 'pagos_no_recurrentes.proyecto_id')
    ->select('pagos_no_recurrentes.*','proveedores.limite_credito as ProvLimiteCredito','proveedores.dia_credito as ProvDiasCredito',
    'proveedores.banco_transferencia as bancos','proveedores.numero_cuenta as numcuenta','proveedores.clabe as clabe',
    'proveedores.nombre as NombreProveedor','proveedores.razon_social','ordenes_compras.folio as FolioOrdendeCompra','proyectos.nombre_corto as NombreProyecto',
    'proyectos.ciudad as CiudadProyecto','condicion_pago.nombre as condicion_pago_nombre', 'moneda')
    ->where('pagos_no_recurrentes.id','=',$id)
    ->first();

    return response()->json($pagosNoRecurrentes);
  }

  /**
   * [Guarda en BD un registro en tabla pagos_no_recurrentes
   * respetando las reglas definidas]
   * @param  Request  $request [Objeto con los datos del POST]
   * @return Response          [Array con estatus true]
   */
  public function store(Request $request)
  {
    try{
    if (!$request->ajax()) return redirect('/');
    $pagosNoRecurrentes = new PagosNoRecurrentes();
    $pagosNoRecurrentes->fill($request->all());
    Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
     Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
    $pagosNoRecurrentes->save();

    return response()->json(array(
      'status' => true
    ));
    return response()->json($pagosNoRecurrentes);
     } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  /**
   * [Actualiza en BD un registro en la tabla pagos_no_recurrentes
   * respetando las reglas definidas]
   * @param  Request  $request [Objeto con los datos del PUT]
   * @return Response          [Array con estatus true]
   */
  public function update(Request $request)
  {
    if(!$request->ajax()) return redirect('/');

    $pagosNoRecurrentes = PagosNoRecurrentes::findOrFail($request->id);
    $pagosNoRecurrentes->fill($request->all());
    Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
    $pagosNoRecurrentes->save();

    return response()->json(array(
      'status' => true
    ));
  }

  /**
   * [Consulta en la BD de la tabla pagos_no_recurrentes con condición = 2, ademas de sus
   * tablas relacionadas proveedores, ordenes_compras y proyectos]
   * @return Response [Array en formato JSON]
   */
  public function compraporautorizar()
  {
    $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
    ->leftJoin('proveedores AS P','P.id','=','pagos_no_recurrentes.proveedor_id')
    ->leftJoin('ordenes_compras AS OC','OC.id','=','pagos_no_recurrentes.ordenes_comp_id')
    ->leftJoin('proyectos AS PR','PR.id','=','pagos_no_recurrentes.proyecto_id')
    ->select('pagos_no_recurrentes.*','P.razon_social','OC.folio','OC.moneda','PR.nombre','PR.nombre_corto','OC.total')
    ->where('pagos_no_recurrentes.condicion','=','2')->get();

    return response()->json($pagosNoRecurrentes);
  }

  /**
   * [Consulta en BD de la tabla pagos_no_recurrentes con el id proporcionado,
   * ademas de sus tablas relacionadas proveedores, ordenes_compras, condicion_pago y proyectos]
   * @param  Int      $id [id recibido del GET]
   * @return Response     [Array en formato JSON]
   */
  public function comprasautorizadas_by_id($id)
  {
    $arreglo = [];
    $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
    ->leftJoin('proveedores AS P', 'P.id', '=', 'pagos_no_recurrentes.proveedor_id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id', '=', 'pagos_no_recurrentes.ordenes_comp_id')
    ->leftJoin('condicion_pago AS CP','CP.id','=','OC.condicion_pago_id')
    ->leftJoin('proyectos AS PR', 'PR.id', '=', 'pagos_no_recurrentes.proyecto_id')
    ->select('pagos_no_recurrentes.*','PR.nombre AS nombre_proyecto','PR.nombre_corto','CP.id AS condicion_pago_id','CP.nombre AS nombre_condicion_pago','OC.id AS orden_compra_id','OC.folio','P.banco_transferencia','P.numero_cuenta','P.clabe','P.razon_social','OC.tipo_cambio','OC.moneda','OC.cie')
    ->where('pagos_no_recurrentes.id','=',$id)
    ->first();

    return response()->json($pagosNoRecurrentes);
  }

  /**
   * [Consulta en BD anidada de las tablas pagos_no_recurrentes,requisicion_has_ordencompras
   * donde id = $id y articulo_id y servicio_id != NULL respectivamente]
   * @param  Int      $id [id recibido del GET]
   * @return Response     [Array en formato JSON]
   */
  public function compraporautorizar_by_id($id)
  {
    $arreglo = [];
    $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
    ->leftJoin('proveedores AS P', 'P.id', '=', 'pagos_no_recurrentes.proveedor_id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id', '=', 'pagos_no_recurrentes.ordenes_comp_id')
    ->leftJoin('condicion_pago AS CP','CP.id','=','OC.condicion_pago_id')
    ->leftJoin('proyectos AS PR', 'PR.id', '=', 'pagos_no_recurrentes.proyecto_id')
    ->select('pagos_no_recurrentes.*','PR.nombre AS nombre_proyecto','PR.nombre_corto','CP.id AS condicion_pago_id',
    'CP.nombre AS nombre_condicion_pago','OC.moneda','OC.id AS orden_compra_id','OC.folio','OC.fecha_probable_pago',
    'OC.prioridad','P.razon_social')
    ->where('pagos_no_recurrentes.id','=',$id)
    ->first();
    $partida_a = DB::table('requisicion_has_ordencompras')
    ->leftJoin('requisiciones AS R', 'R.id', '=', 'requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('articulos AS A', 'A.id', '=', 'requisicion_has_ordencompras.articulo_id')
    ->leftJoin('proyectos AS P', 'P.id', '=', 'R.proyecto_id')
    ->select('requisicion_has_ordencompras.*','R.id AS requisicione_id','R.folio AS requisicione_folio','R.fecha_solicitud AS requisicione_fecha_solicitud','P.nombre AS proyecto_nombre','A.id AS articulo_id','A.descripcion AS articulo_descripcion')
    ->where('requisicion_has_ordencompras.orden_compra_id', '=', $pagosNoRecurrentes->orden_compra_id)->where('requisicion_has_ordencompras.articulo_id','!=','null')
    ->get()->toArray();
    $partida_s = DB::table('requisicion_has_ordencompras')
    ->leftJoin('requisiciones AS R', 'R.id', '=', 'requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('servicios AS S', 'S.id', '=', 'requisicion_has_ordencompras.servicio_id')
    ->leftJoin('proyectos AS P', 'P.id', '=', 'R.proyecto_id')
    ->select('requisicion_has_ordencompras.*','R.id AS requisicione_id','R.folio AS requisicione_folio','R.fecha_solicitud AS requisicione_fecha_solicitud','P.nombre AS proyecto_nombre','S.id AS articulo_id','S.nombre_servicio AS articulo_descripcion')
    ->where('requisicion_has_ordencompras.orden_compra_id', '=', $pagosNoRecurrentes->orden_compra_id)->where('requisicion_has_ordencompras.servicio_id','!=','null')
    ->get()->toArray();
    $partida = array_merge($partida_a,$partida_s);
    $arreglo [] = [
      'PagosNoRecurrentes' => $pagosNoRecurrentes,
      'partidacompra' => $partida,
    ];
    return response()->json($arreglo);
  }

  /**
   * [Modifica en BD de la tabla pagos_no_recurrentes el campo condición
   * respetando las condiciones definidas]
   * @param  Request  $request [Objeto con los datos del POST]
   * @return Response          [Array con estatus true]
   */
  public function autorizarcompra(Request $request)
  {
    //Autorizado
    if ($request->edo == 1) {
      $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('id','=',$request->id)->first();
      $pagosNoRecurrentes->condicion = 4;
      $pagosNoRecurrentes->save();

      $compras = Compras::
      join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
      ->select('ordenes_compras.*','p.razon_social')
      ->where('ordenes_compras.id',$pagosNoRecurrentes->ordenes_comp_id)->first();
      $this->envioCorreo($compras);
    }
    //No autorizado
    elseif ($request->edo == 0) {
      $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('id','=',$request->id)->first();
      $pagosNoRecurrentes->condicion = 1;
      $pagosNoRecurrentes->save();
      $compras = \App\Compras::where('id','=',$pagosNoRecurrentes->ordenes_comp_id)->first();
      $compras->condicion = 1;
      $compras->save();
      if($compras->condicion_pago_id == 1)
      {
        $credito = \App\Credito::where('proveedor_id','=',$pagosNoRecurrentes->proveedor_id)->first();
        $credito->monto_debe = ($credito->monto_debe) - ($pagosNoRecurrentes->monto);
        $credito->monto_disponible = ($credito->monto_disponible) + ($pagosNoRecurrentes->monto);
        $credito->save();
      }
    }
    return response()->json(array('status' => true));
  }

  /**
   * [Consulta en BD de la tabla pagos_no_recurrentes donde el campo condición = 4]
   * @return Response  [Array en formato JSON]
   */
  public function comprasautorizadas(){
    $pagosNoRecurrentes = DB::table('pagos_no_recurrentes')
    ->leftJoin('proveedores AS P','P.id','=','pagos_no_recurrentes.proveedor_id')
    ->leftJoin('ordenes_compras AS OC','OC.id','=','pagos_no_recurrentes.ordenes_comp_id')
    ->leftJoin('proyectos AS PR','PR.id','=','pagos_no_recurrentes.proyecto_id')
    ->select('pagos_no_recurrentes.*','P.razon_social','OC.folio','PR.nombre_corto','PR.nombre','OC.total','P.clabe')
    ->where('pagos_no_recurrentes.condicion','=','4')->get();


    return response()->json($pagosNoRecurrentes);
  }

  /**
   * [Consulta en BD los registros de la tabla pagos_no_recurrentes]
   * @param  Request  $request [URL del GET]
   * @return Response          [Array en formato JSON]
   */
  public function getList(Request $request)
  {
    $pagosNoRecurrentes = PagosNoRecurrentes::select('id')->orderBy('id','desc')->get()->toArray();
    return response()->json($pagosNoRecurrentes);

  }

  public function envioCorreo($compra)
  {
    try {
      $empleado_solicita = $compra->elabora_empleado_id;
      $users = User::
      where('users.condicion','1')
      ->where('users.email','LIKE','ramon.cruzm@conserflow.com')
      // ->where('users.email','LIKE','gregorio.romero@conserflow.com')
      ->select('users.*')
      ->first();
      $mensaje = 'Compra por autorizar pago';
      if (isset($users) == true) {
        $partida_a = DB::table('requisicion_has_ordencompras')
        ->leftJoin('articulos AS A', 'A.id', '=', 'requisicion_has_ordencompras.articulo_id')
        ->select('requisicion_has_ordencompras.*','A.id AS articulo_id','A.descripcion AS articulo_descripcion')
        ->where('requisicion_has_ordencompras.orden_compra_id', '=', $compra->id)->where('requisicion_has_ordencompras.articulo_id','!=','null')
        ->get()->toArray();
        $partida_s = DB::table('requisicion_has_ordencompras')
        ->leftJoin('servicios AS S', 'S.id', '=', 'requisicion_has_ordencompras.servicio_id')
        ->select('requisicion_has_ordencompras.*','S.id AS articulo_id','S.nombre_servicio AS articulo_descripcion')
        ->where('requisicion_has_ordencompras.orden_compra_id', '=', $compra->id)->where('requisicion_has_ordencompras.servicio_id','!=','null')
        ->get()->toArray();
        $partida = array_merge($partida_a,$partida_s);
        $moneda = ($compra->moneda == 1 )? ('USD') : ($compra->moneda == 2 ? 'MXN' : 'EUR');
        $empleado = \App\Empleado::where('id',$users->empleado_id)->first();
        $emmpleado_solicita = \App\Empleado::where('id',$empleado_solicita)->first();
        $hoy = date("Y-m-d");
        $data = [
          'nombre' => $empleado->nombre.' '.$empleado->ap_paterno.' '.$empleado->ap_materno,
          'correo_electronico' => $users->email,
          'fecha' => $hoy,
          'folio' => $compra->folio,
          'proveedor' => $compra->razon_social,
          'monto' => $compra->total,
          'moneda' => $moneda,
          'mensaje' => $mensaje,
          'empleado_solicita' => $emmpleado_solicita->nombre.' '.$emmpleado_solicita->ap_paterno.' '.$emmpleado_solicita->ap_materno,
          'partidas' => $partida,
        ];
        Mail::send('emails.autorizacomprapago', $data, function($message) use ($data, $users, $mensaje) {
          $core= $users->email;
          $message->to($core, $mensaje)
          ->subject($mensaje);
          $message->from('webmaster@conserflow.com','Conserflow');
        });
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }
}
