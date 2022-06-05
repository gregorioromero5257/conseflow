<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;
use App\Compras;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;


class RequisicionporCompraController extends Controller
{

  public function listadoCompras(Request $request,$id)
  {
    $listado = DB::table('ordenes_compras')
    ->leftJoin('proyectos','proyectos.id','=','ordenes_compras.proyecto_id')
    ->select ('ordenes_compras.*','proyectos.nombre_corto as nombreCorto')
    ->where('ordenes_compras.proyecto_id','=',$id)
    ->get();

      return response()->json($listado);
  }


  public function pdfGenerado($id)
  {

      $arreglo = [];
        $subtotal_info = 0;
        $subtotal_info_dos = 0;
        ini_set('max_execution_time', 1000);

        $proyecto_ = Compras::join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
        ->where('ordenes_compras.id','=',$id)->first();
        $ordenesreq_v = [];

        $compra =DB::select("SELECT oc.*,cp.nombre AS condicion_pago,p.razon_social AS razonsocialp,p.banco_transferencia AS bancotransferenciap, p.tipo_cambio AS tipocambiop,p.numero_cuenta AS numerocuentap,p.clabe AS clabep, CONCAT (ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nomnree, CONCAT (ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombrea FROM ordenes_compras oc LEFT JOIN proveedores p ON p.id = oc.proveedore_id LEFT JOIN estado_compras ec ON ec.id = oc.estado_id LEFT JOIN empleados ee ON ee.id = oc.elabora_empleado_id LEFT JOIN empleados ea ON ea.id = oc.autoriza_empleado_id LEFT JOIN condicion_pago cp ON cp.id = oc.condicion_pago_id WHERE oc.id = '$id' LIMIT 1");
        $ordenesreq_a = DB::select("SELECT rhc.*, a.unidad AS unidadA, a.descripcion AS descripcionA, p.nombre_corto AS nombrep,r.folio AS folior FROM requisicion_has_ordencompras rhc LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id LEFT JOIN proyectos p ON p.id = r.proyecto_id LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id LEFT JOIN articulos a ON a.id = rhc.articulo_id WHERE rhc.orden_compra_id = '$id' AND rhc.articulo_id != 'null'");
        $ordenesreq_s = DB::select("SELECT rhc.*, s.unidad_medida AS unidadA, s.nombre_servicio AS descripcionA, p.nombre_corto AS nombrep,r.folio AS folior FROM requisicion_has_ordencompras rhc LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id LEFT JOIN proyectos p ON p.id = r.proyecto_id LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id LEFT JOIN servicios s ON s.id = rhc.servicio_id WHERE rhc.orden_compra_id = '$id' AND rhc.servicio_id != 'null'");

          if ($proyecto_->nombre_corto === 'MANTENIMIENTO VEHICULAR') {
            $ordenesreq_v = DB::select("SELECT rhc.*, 'VEHÍCULO' AS unidadA, v.descripcion AS descripcionA, p.nombre_corto AS nombrep,r.folio AS folior
              FROM requisicion_has_ordencompras rhc
              LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id
              LEFT JOIN proyectos p ON p.id = r.proyecto_id
              LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id
              LEFT JOIN cat_mantenimiento_vehiculos v ON v.id = rhc.vehiculo_id WHERE rhc.orden_compra_id = '$id' AND rhc.vehiculo_id != 'null'");
              // $ordenesreq = array_merge($ordenesreq_a, $ordenesreq_s,$ordenesreq_v);

          }elseif ($proyecto_->nombre_corto === 'VEHÍCULOS') {
            $ordenesreq_v = DB::select("SELECT rhc.*, 'VEHÍCULO' AS unidadA, v.descripcion AS descripcionA, p.nombre_corto AS nombrep,r.folio AS folior
              FROM requisicion_has_ordencompras rhc
              LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id
              LEFT JOIN proyectos p ON p.id = r.proyecto_id
              LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id
              LEFT JOIN vehiculos v ON v.id = rhc.vehiculo_id WHERE rhc.orden_compra_id = '$id' AND rhc.vehiculo_id != 'null'");
              // $ordenesreq = array_merge($ordenesreq_a, $ordenesreq_s,$ordenesreq_v);

          }
          $ordenesreq = array_merge($ordenesreq_a, $ordenesreq_s,$ordenesreq_v);

          // $ordenesreq = array_merge($ordenesreq_a, $ordenesreq_s);



        $coti = \App\PartidaCotizacionCompra::where('orden_compra_id','=',$id)
        ->join('cotizaciones AS c','c.id','=','partida_cotizacion_compra.cotizacion_id')->select('partida_cotizacion_compra.*','c.folio')->get();


        $folio_requis_art = DB::select("SELECT DISTINCT(r.folio) FROM requisicion_has_ordencompras rhc LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id LEFT JOIN proyectos p ON p.id = r.proyecto_id LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id LEFT JOIN articulos a ON a.id = rhc.articulo_id WHERE rhc.orden_compra_id = '$id' AND rhc.articulo_id != 'null'");
        $folio_requis_ser = DB::select("SELECT DISTINCT(r.folio) FROM requisicion_has_ordencompras rhc LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id LEFT JOIN proyectos p ON p.id = r.proyecto_id LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id LEFT JOIN servicios s ON s.id = rhc.servicio_id WHERE rhc.orden_compra_id = '$id' AND rhc.servicio_id != 'null'");

          if ($proyecto_->nombre_corto === 'MANTENIMIENTO VEHICULAR') {
            $folio_requis_veh = DB::select("SELECT DISTINCT(r.folio) FROM requisicion_has_ordencompras rhc
            LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id
            LEFT JOIN proyectos p ON p.id = r.proyecto_id
            LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id
            LEFT JOIN cat_mantenimiento_vehiculos v ON v.id = rhc.vehiculo_id WHERE rhc.orden_compra_id = '$id' AND rhc.vehiculo_id != 'null'");
            $folios_requis = array_merge($folio_requis_art, $folio_requis_ser,$folio_requis_veh);

          }elseif ($proyecto_->nombre_corto === 'VEHÍCULOS') {
            $folio_requis_veh = DB::select("SELECT DISTINCT(r.folio) FROM requisicion_has_ordencompras rhc
            LEFT JOIN requisiciones r ON r.id = rhc.requisicione_id
            LEFT JOIN proyectos p ON p.id = r.proyecto_id
            LEFT JOIN ordenes_compras oc ON oc.id = rhc.orden_compra_id
            LEFT JOIN vehiculos v ON v.id = rhc.vehiculo_id WHERE rhc.orden_compra_id = '$id' AND rhc.vehiculo_id != 'null'");
            $folios_requis = array_merge($folio_requis_art, $folio_requis_ser,$folio_requis_veh);

          }

        $folios_requis = array_merge($folio_requis_art, $folio_requis_ser);

        $hoy = date("d/m/Y");

        $anio = substr($compra[0]->fecha_orden, 0 , 4);
        $mes = substr($compra[0]->fecha_orden, 5 , -3);
        $dia = substr($compra[0]->fecha_orden, 8);

        $fechafinal = $dia.'/'.$mes.'/'.$anio;

        $count =1;

        $partidascount = \App\requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id', '=', $id)->count();
        $subtotal = \App\requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
        ->select(DB::raw("SUM(cantidad * precio_unitario) AS subtotal"))->first();
        $subtotal_info_dos = $subtotal->subtotal;
        $requisicion_has_compra = \App\requisicionhasordencompras::where('orden_compra_id',$id)->first();

        $partidas_requisisciones = DB::select("SELECT comentario FROM partidas_requisiciones WHERE requisicione_id =  '$requisicion_has_compra->requisicione_id'");
        $valor = 14;
        $valora = $valor - $partidascount;
        if($compra[0]->descuento > 0){
          $subtotal_info = (($subtotal->subtotal/100)*$compra[0]->descuento);
          $subtotal_info_dos =  $subtotal->subtotal - (($subtotal->subtotal/100)*$compra[0]->descuento);
        }
        // $iva=($subtotal[0]->subtotal/100)*16.00;
        $impuestos = \App\Impuesto::where('orden_compra_id','=',$id)->get();
        $arreglos = '';
        $totalesimpuestos = array();
        $totalesimpuestos_retenidos = array();
        if (count($impuestos) != 0) {
          foreach ($impuestos as $key => $value) {
            if ($value->retenido == 1) {
              $totalim = ($subtotal_info_dos/100)*$value->porcentaje;
            array_push($totalesimpuestos_retenidos, $totalim);
            }
            if ($value->retenido == 0) {
              $totalim = ($subtotal_info_dos/100)*$value->porcentaje;
            array_push($totalesimpuestos, $totalim);
            }


            $arreglo [] = [
              'impuestos' => $value,
              'totalim' => $totalim,
            ];
          }
        }

        // $total =   array_sum($totalesimpuestos) + $subtotal_info_dos;
        $total = ($subtotal_info_dos + array_sum($totalesimpuestos)) - array_sum($totalesimpuestos_retenidos);
        $iva = number_format(12, 2);
        $total = number_format($total, 2);
        $ordenCompra = \App\Compras::findOrFail($id);
        $ordenCompra->total = $total;
        $ordenCompra->save();

        $folio_com = explode('-',$compra[0]->folio);



  $pdf = PDF::loadView('pdf.requisicionporcompra',compact('compra','partidas_requisisciones','ordenesreq','folio_com','fechafinal','partidascount','coti','count','valora','hoy','impuestos','arreglo','arreglos','subtotal','subtotal_info','subtotal_info_dos','iva','total','folios_requis','proyecto_'));
  $pdf->setPaper('A4', 'landscape');
  // return $pdf->download('cv-interno.pdf');
  return $pdf->stream();
}
}
