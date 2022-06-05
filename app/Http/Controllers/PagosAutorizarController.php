<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PagosNoRecurrentes;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\User;
use Mail;
use App\Compras;


class PagosAutorizarController extends Controller
{
    //

    public function index()
    {

      $numero_mese = [];
      $num_meses = [1,2,3,4,5,6,7,8,9,10,11,12];
      for ($i=0; $i < 3; $i++) {
        array_push($numero_mese, ($mes = date('n') + $i));
        $mes = date('m') + $i;
        if ($mes < 10) {
          $mes = '0'.$mes;
        }
        $pagos = PagosNoRecurrentes::
        leftJoin('ordenes_compras AS OC','OC.id','=','pagos_no_recurrentes.ordenes_comp_id')
        ->leftJoin('proveedores AS P','P.id','=','OC.proveedore_id')
        ->join('empleados AS EE','EE.id','=','OC.elabora_empleado_id')
        ->join('empleados AS EA','EA.id','=','OC.autoriza_empleado_id')
        ->whereIn('pagos_no_recurrentes.condicion',['6','4','7'])
        ->where('OC.fecha_probable_pago','like', date('Y-').$mes.'%')
        ->select('OC.*','P.razon_social',DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_empleado_elabora"),
        DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_empleado_autoriza"),'pagos_no_recurrentes.monto',
        'pagos_no_recurrentes.condicion AS condicion_pnr','pagos_no_recurrentes.id AS pnr_id')
        ->orderBy('OC.fecha_probable_pago','asc')->get();
        $arreglo [] = $pagos;
        $meses [] = Utilidades::meses($mes) .' '. date('Y');


      }
      // $arreglo  = [
      //   [1,2,3,4,5,6],
      //   [2,3,4,7,1,3],
      //   [5,4,2,7,8,9]
      // ];
      return response()->json(array('a' => $arreglo, 'mhy' => $meses));
    }

    public function autorizarpagos($id)
    {
      try {
        $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('id','=',$id)->first();
        $pagosNoRecurrentes->condicion = 4;
        $pagosNoRecurrentes->fecha_autorizado = date('Y-m-d');
        $pagosNoRecurrentes->save();

        $compras = Compras::
        join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
        ->select('ordenes_compras.*','p.razon_social','p.banco_transferencia','p.numero_cuenta','p.clabe')
        ->where('ordenes_compras.id',$pagosNoRecurrentes->ordenes_comp_id)->first();
        $this->envioCorreo($compras);

        return response()->json(array('status' => true));
      } catch (\Throwable $e) {
        Utilidades::errors($e);
      }
    }

    public function envioCorreo($compra)
    {
      try {
        $empleado_solicita = $compra->elabora_empleado_id;
        $empleado_autoriza = $compra->autoriza_empleado_id;
        $users = User::
        where('users.condicion','1')
        ->where('users.email','LIKE','luisa.flores@conserflow.com	')
        // ->where('users.email','LIKE','gregorio.romero@conserflow.com')
        ->select('users.*')
        ->first();

        $mensaje = 'Compra autorizada por pagar';
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
          $emmpleado_autoriza = \App\Empleado::where('id',$empleado_autoriza)->first();
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
            'banco_transferencia' => $compra->banco_transferencia,
            'numero_cuenta' => $compra->numero_cuenta,
            'clabe' => $compra->clabe,
            'empleado_solicita' => $emmpleado_solicita->nombre.' '.$emmpleado_solicita->ap_paterno.' '.$emmpleado_solicita->ap_materno,
            'empleado_autoriza' => $emmpleado_autoriza->nombre.' '.$emmpleado_autoriza->ap_paterno.' '.$emmpleado_autoriza->ap_materno,
          ];
          Mail::send('emails.autorizacomprapagar', $data, function($message) use ($data, $users, $mensaje) {
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

    public function pagosvencidos()
    {

        $mes = date('m');
        if ($mes < 10) {
          $mes = '0'.$mes;
        }
      $pagos = PagosNoRecurrentes::
      leftJoin('ordenes_compras AS OC','OC.id','=','pagos_no_recurrentes.ordenes_comp_id')
      ->leftJoin('proveedores AS P','P.id','=','OC.proveedore_id')
      ->join('empleados AS EE','EE.id','=','OC.elabora_empleado_id')
      ->join('empleados AS EA','EA.id','=','OC.autoriza_empleado_id')
      ->whereIn('pagos_no_recurrentes.condicion',['6','4'])
      ->whereDate('OC.fecha_probable_pago','<', date('Y-').$mes.'-01')
      ->select('OC.*','P.razon_social',DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS nombre_empleado_elabora"),
      DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombre_empleado_autoriza"),'pagos_no_recurrentes.monto',
      'pagos_no_recurrentes.condicion AS condicion_pnr','pagos_no_recurrentes.id AS pnr_id')
      ->orderBy('OC.fecha_probable_pago','asc')->get();

      return response()->json($pagos);
    }
// ALTER TABLE conserflowMayoDos.pagos_no_recurrentes ADD fecha_autorizado DATE NULL;


}
