<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use Mail;
use Illuminate\Validation\Rule;
use \App\Http\Helpers\Utilidades;
use App\User;
use App\Compras;


class CreditoController extends Controller
{
  /**
  * [index Consulta en la BD de la tabla credito]
  * @return Response [description]
  */
  public function index()
  {
    $credito = DB::table('credito')->leftJoin('proveedores AS P','P.id','=','credito.proveedor_id')->select('credito.*','P.razon_social')->get();
    return response()->json($credito);
  }

  /**
  * [store Guardado en la BD respetando las condiciones definidas]
  * @param  Request $request [description]
  * @return Response           [description]
  */
  public function store(Request $request)
  {
    try {
      $estado = $request->estado;
      $id = $request->ordenes_comp_id;

      if ($estado == 1) {
        //exede le limte de credito y hay que cambiar su condicion_pago_id si se encuentra como "condicion_pago = credito"
        //se cambia en pagos no recurrentes el rango_dias y eventos, ademas de no restar el credito dela tabla credito del provedor
        // $compra = \App\Compras::where('id','=',$id)->first();
        $compra = Compras::
        join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
        ->select('ordenes_compras.*','p.razon_social')
        ->where('ordenes_compras.id',$id)->first();
        $correo = $this->envioCorreo($compra);
        // if ($correo == true) {
        $compra->condicion_pago_id = 2;
        $compra->condicion = 2;
        Utilidades::auditar($compra, $compra->id);
        $compra->save();
        $total = str_replace(",", "", $compra->total);
        $requisicion_ordencompra = \App\requisicionhasordencompras::where('orden_compra_id','=',$id)->first();
        $requisiciones = \App\Requisicion::where('id','=',$requisicion_ordencompra->requisicione_id)->first();
        $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('ordenes_comp_id','=',$id)->first();
        $pagosNoRecurrentes->proyecto_id = $requisiciones->proyecto_id;
        $pagosNoRecurrentes->monto = $total;
        $pagosNoRecurrentes->rango_dias = 0;
        $pagosNoRecurrentes->eventos = 1;
        $pagosNoRecurrentes->condicion = 2;
        Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
        $pagosNoRecurrentes->save();
        return response()->json(array('status' => true));
        // }
        // else {
        //   return response()->json(array(
        //     'status' => false,
        //     'errors' => 'Error al enviar correo <br> No existe el usuario/ No tiene correo <br> Contacte al administrador y recarge la página'
        //   ));
        // }
      }
      elseif ($estado == 2) {
        //compra normal solo se registra y y se manda al dashboard de contabilidad para se agendado
        //se registra en pagos no recurrentes y se resta en el credito del proveedor si es que se eligio esa opcion(credito)
        $compra = Compras::
        join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
        ->select('ordenes_compras.*','p.razon_social')
        ->where('ordenes_compras.id',$id)->first();
        $correo = $this->envioCorreo($compra);
        // if ($correo == true) {
        $compra->condicion = 2;
        $compra->save();
        $total = str_replace(",","",$compra->total);
        $requisicion_ordencompra = \App\requisicionhasordencompras::where('orden_compra_id','=',$id)->first();
        $requisiciones = \App\Requisicion::where('id','=',$requisicion_ordencompra->requisicione_id)->first();
        $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('ordenes_comp_id','=',$id)->first();
        $pagosNoRecurrentes->proyecto_id = $requisiciones->proyecto_id;
        $pagosNoRecurrentes->monto = $total;
        $pagosNoRecurrentes->condicion = 2;
        Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
        $pagosNoRecurrentes->save();
        if ($compra->condicion_pago_id == 1) {
          $credito = \App\Credito::where('proveedor_id','=',$pagosNoRecurrentes->proveedor_id)->first();
          if (isset($credito) == true) {
            $monto_disponible = ($credito->monto_credito) - $total;
            $credito->monto_debe = ($credito->monto_debe) + $total;
            $credito->monto_disponible = $monto_disponible;
            Utilidades::auditar($credito, $credito->id);
            $credito->save();
          }
        }
        return response()->json(array('status' => true));
        // }
        // else {
        //   return response()->json(array(
        //     'status' => false,
        //     'errors' => 'Error al enviar correo <br> No existe el usuario/ No tiene correo <br> Contacte al administrador y recarge la página'
        //   ));
        // }
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  public function envioCorreo($compra)
  {
    try {
      $empleado_solicita = $compra->elabora_empleado_id;
      $users = User::leftJoin('empleados AS E','E.id','=','users.empleado_id')
      ->leftJoin('puestos AS P','P.id','=','E.puesto_id')
      ->where('P.nombre','=','Jefe de Contraloria')
      ->where('users.condicion','1')
      ->select('users.*')
      ->first();
      $mensaje = 'Compra por autorizar';
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
        $moneda = $compra->moneda == 1 ? 'USD' : $compra->moneda == 2 ? 'MXN' : 'EUR';
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
          'partidas' => $partida,
          'empleado_solicita' => $emmpleado_solicita->nombre.' '.$emmpleado_solicita->ap_paterno.' '.$emmpleado_solicita->ap_materno,
        ];
        Mail::send('emails.autorizacompra', $data, function($message) use ($data, $users, $mensaje) {
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
