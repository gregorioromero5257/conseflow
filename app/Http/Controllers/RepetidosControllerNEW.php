<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\RelacionSpool;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Controltiempo;
use App\Compras;
use App\Contrato;
use App\ContratoDBP;
use App\ComprasDBP;
use App\Proveedor;
use App\ProveedorDBP;
use App\Empleado;
use App\EmpleadoDBP;
use App\RequisicionhasordencomprasDBP;
use App\requisicionhasordencompras;
use App\ArticuloDBP;
use App\Articulo;
use App\CatServicios;
use App\CatServiciosDBP;
use App\Requisicion;
use App\RequisicionDBP;
use App\PartidaRe;
use App\PartidasReDBP;
use App\Adicionales;
use App\Sueldo;
use App\SueldoDBP;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosServicios;
use App\FacturasEntradas;
use App\Partidas;
use App\PagoCompra;
use App\Movimiento;
use App\PagosNoRecurrentes;
use DateInterval;
use DatePeriod;
use DateTime;
use App\SueldoSemanaEmpleado;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\EquiposImport;

class RepetidosController extends Controller
{

  // public function repe()
  // {
  //   $arreglo = [];
  //   $repe = Controltiempo::get();
  //
  //   foreach ($repe as $key => $value) {
  //    $c = Controltiempo::where('fecha',$value->fecha)->where('empleado_asignado_id',$value->empleado_asignado_id)->get();
  //    if (count($c) > 1) {
  //      // $d = Controltiempo::where('id',$value->id)->delete();
  //      $arreglo [] = [
  //         $value
  //      ];
  //    }
  //   }
  //
  //  return response()->json($arreglo);
  //
  // }
  //40
  // $compras = Compras::where('proyecto_id','98')->orderBy('folio','ASC')->get();
  //
  // foreach ($compras as $key => $value) {
  //   $valores = explode('-',$value->folio);
  //   $oc = Compras::where('id',$value->id)->first();
  //   $oc->folio = $valores[0].'-'.$valores[1].'-'.$valores[2].'-'.$valores[3].'-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
  //   // $oc->folio = 'OC-CONSERFLOW-020-TI-'.str_pad(($key + 1), 3, "0", STR_PAD_LEFT);
  //   $oc->save();
  //
  // }
  public function getDia($fecha)
  {
    $dias = array('Lunes','Martes','Miercoles','Jueves','Viernes','Sabado','Domingo');

    $dia = $dias[(date('N', strtotime($fecha))) - 1];

    return $dia;
  }

  public function validateDate($date, $format = 'Y-m-d H:i:s')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}

  public function facturasentradasfixed($id)

{
  $requisiciones = DB::table('requisiciones AS r')->where('folio','LIKE','%PETROFAC PAQUETE 1%')->get();
  $arreglo = [];
  foreach ($requisiciones as $key => $value) {

    $valores = explode('&',$value->folio);
    $arreglo [] = count($valores);

  }
  return response()->json($arreglo);

  $collection = Excel::import(new EquiposImport, 'PlantillaInventarioF.xlsx', 'temporal');
    return response()->json(['status' => true]);


  $req = DB::table('requisicion_has_ordencompras AS rhoc')
  ->join('requisiciones AS r','r.id','rhoc.requisicione_id')
  ->select('rhoc.*','r.folio')
  ->where('orden_compra_id',$id)->get();
  return response()->json($req);

  $oc = DB::table('ordenes_compras AS oc')
  ->join('proyectos AS p','p.id','oc.proyecto_id')
  ->where('proveedore_id','1')
  ->where('oc.proyecto_id','105')
  ->select('oc.*')
  // ->select('oc.proyecto_id','p.nombre_corto')
  ->get();
  return response()->json($oc);

  $data = DB::table('ordenes_compras AS oc')
  ->join('proyectos AS p','p.id','oc.proyecto_id')
  ->where('proveedore_id','1')
  ->whereNotIn('oc.proyecto_id',['0','1'])
  ->select('oc.proyecto_id','p.nombre_corto')
  ->groupBy('oc.proyecto_id')
  ->get();



  //////////////////7

  // $fechauno = '2021-03-01';
  // $fechados = '2021-03-28';
  //
  // $fechaInicio=strtotime($fechauno);
  // $fechaFin=strtotime($fechados);
  // $a = [];
  // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //   $a [] = date("Y-m-d", $i);
  // }

  // return response()->json($a);

  $collection = Excel::import(new EquiposImport, 'Ct.xlsx', 'temporal');
    return response()->json(['status' => true]);

  $pagos = \App\PagoCompra::get();
  foreach ($pagos as $key => $value) {
    if ($value->num_poliza != null) {
      $d = new \App\PolizasPagosCompras();
      $d->pagos_compras_id = $value->id;
      $d->num_poliza = $value->num_poliza;
      $d->poliza = $value->poliza;
      $d->save();
    }
  }
  return response()->json(['status' => true]);

  ////////////////////
  $collection = Excel::import(new EquiposImport, 'sat_percepcion.xlsx', 'temporal');
    return response()->json(['status' => true]);

 $compras = DB::table('ordenes_compras AS oc')->where('oc.proveedore_id','1')->select('id')->get();
 $arreglo = [];
 foreach ($compras as $key => $value) {
   $rhoc = DB::table('requisicion_has_ordencompras AS rhc','rhc.orden_compra_id',$value->id)
   ->select('id')->get();

   $arreglo [] = [
     'oc' => $value,
     'rhc' => $rhoc,
   ];
 }

 return response()->json($compras);


  /////////////////////////
  $collection = Excel::import(new EquiposImport, 'Inventario.xlsx', 'temporal');

    return response()->json(['status' => true]);

////////

$equipos = DB::table('equipos_catalogo')->get();
    $arreglo = [];

    foreach ($equipos as $key => $value) {
      if ($this->validateDate($value->fecha_servicio) && $this->validateDate($value->proxima_fecha)) {

        $servicios = new \App\ServiciosEquiposCalibracion();
        $servicios->equipos_catalogo_id = $value->id;
        $servicios->fecha_servicio = substr($value->fecha_servicio,0,10);
        $servicios->proxima_fecha = substr($value->proxima_fecha,0,10);
        $servicios->estado = 0;
        $servicios->save();



      }elseif ($this->validateDate($value->fecha_servicio) &&  !$this->validateDate($value->proxima_fecha)) {
        $servicios = new \App\ServiciosEquiposCalibracion();
        $servicios->equipos_catalogo_id = $value->id;
        $servicios->fecha_servicio = substr($value->fecha_servicio,0,10);
        $servicios->estado = 0;
        $servicios->save();


      }elseif ( $this->validateDate($value->proxima_fecha) && !$this->validateDate($value->fecha_servicio) ) {
        $servicios = new \App\ServiciosEquiposCalibracion();
        $servicios->equipos_catalogo_id = $value->id;
        $servicios->proxima_fecha = substr($value->proxima_fecha,0,10);
        $servicios->estado = 0;
        $servicios->save();
      }

    }

    return response()->json($arreglo);

	$collection = Excel::import(new EquiposImport, 'cinco.xlsx', 'temporal');
      return response()->json(['status' => true]);

	  $solis=\App\SolicitudViaticos::get();
           foreach ($solis as $sol) {
               $sol->empleado_user_id=explode("|",$sol->empleado_user_id)[0];
               $sol->update();
           }
    return response()->json(['status' => true]);

    $arreglo_fechas_buscar = [];
    $fechaInicio=strtotime('2020-12-04');
    $fechaFin=strtotime('2021-01-31');

    for($z=$fechaInicio; $z<=$fechaFin; $z+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$z);
    }

    for ($y=0; $y <count($arreglo_fechas_buscar) ; $y++) {
      $dia = $this->getDia($arreglo_fechas_buscar[$y]);
      if ($dia != 'Domingo') {
        $controltiempo = new \App\Controltiempo();
        $controltiempo->fecha = $arreglo_fechas_buscar[$y];
        $controltiempo->empleado_asignado_id = 74;
        $controltiempo->proyecto_id = 105;
        $controltiempo->horas_extras = 0;
        $controltiempo->supervisor_id = 165;
        $controltiempo->save();

      }
    }

    return response()->json(['status' => true]);

    //////////////////////////
    $dto = new DateTime();
    $dto->setISODate(2020, 53);
    $ret['inicio'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['fin'] = $dto->format('Y-m-d');
    return response()->json($ret);

    $partidas_oc_a = DB::table('requisicion_has_ordencompras AS rhoc')
    ->join('articulos AS a','a.id','rhoc.articulo_id')
    ->join('grupos AS g','g.id','a.grupo_id')
    ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
    ->join('proyectos AS pro','pro.id','oc.proyecto_id')
    ->join('proveedores AS p','p.id','oc.proveedore_id')
    ->select('oc.folio','oc.fecha_orden','p.razon_social','g.nombre','oc.condicion_pago_id',
    'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','a.unidad','a.descripcion',
    'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
    'rhoc.comentario','rhoc.id','pro.id AS pro_id')
    ->where('rhoc.articulo_id','!=',NULL)
    ->where('pro.id','!=','1')
    ->get()->toArray();


    $partidas_oc_s = DB::table('requisicion_has_ordencompras AS rhoc')
    ->join('servicios AS s','s.id','rhoc.servicio_id')
    // ->join('grupos AS g','g.id','a.grupo_id')
    ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
    ->join('proyectos AS pro','pro.id','oc.proyecto_id')
    ->join('proveedores AS p','p.id','oc.proveedore_id')
    ->select('oc.folio','oc.fecha_orden','p.razon_social','s.unidad_medida AS nombre','oc.condicion_pago_id',
    'oc.periodo_entrega','oc.comentario_condicion_pago','rhoc.cantidad','s.unidad_medida AS unidad',
    's.nombre_servicio AS descripcion',
    'rhoc.precio_unitario','oc.moneda','rhoc.articulo_id','rhoc.servicio_id','rhoc.requisicione_id','oc.condicion',
    'rhoc.comentario','rhoc.id','pro.id AS pro_id')
    ->where('rhoc.servicio_id','!=',NULL)
    ->where('pro.id','!=','1')
    ->get()->toArray();

    $partidas_oc = array_merge($partidas_oc_a,$partidas_oc_s);
    // return response()->json($partidas_oc);

    $arreglo = [];
    foreach ($partidas_oc as $key => $value) {
      if ($value->articulo_id != '') {

      $partidas_requisiciones = DB::table('partidas_requisiciones AS pr')
      ->join('requisiciones AS r','r.id','pr.requisicione_id')
      ->where('articulo_id',$value->articulo_id)
      ->where('requisicione_id',$value->requisicione_id)
      ->select('r.folio','r.fecha_solicitud','pr.produccion')
      ->first();
$request
      $partidas_entradas_f = DB::table('partidas_entradas AS pe')
      ->join('entradas AS e','e.id','pe.entrada_id')
      // ->join('lotes AS l','l.entrada_id','pe.id')
      // ->join('lote_almacen AS la','la.lote_id','l.id')
      ->select('e.fecha')
      ->where('req_com_id',$value->id)
      ->get();

      $partidas_entradas_c = DB::table('partidas_entradas AS pe')
      ->join('entradas AS e','e.id','pe.entrada_id')
      ->select(DB::raw("SUM(pe.cantidad) AS cantidad"))
      ->where('req_com_id',$value->id)
      ->first();
    }
    if ($value->servicio_id != '') {
      $partidas_requisiciones = DB::table('partidas_requisiciones AS pr')
      ->join('requisiciones AS r','r.id','pr.requisicione_id')
      ->where('articulo_id',$value->servicio_id)
      ->where('requisicione_id',$value->requisicione_id)
      ->select('r.folio','r.fecha_solicitud','pr.produccion')
      ->first();

      $partidas_entradas_f = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
      ->select('servicio_check.fecha')
      ->where('servicio_check.rhoc_id',$value->id)
      ->get();

      $partidas_entradas_c = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
      ->select('rhoc.cantidad')
      ->where('servicio_check.rhoc_id',$value->id)
      ->get();
    }

      $arreglo [] = [
        'a' => $value,
        'b' => $partidas_requisiciones,
        'c' => $partidas_entradas_f,
        'd' => $partidas_entradas_c,
      ];
    }





    return response()->json($arreglo);

  }

  public function facturasentradasfixed_s($id)
  {





    /////////////////
       $orden_compras = DB::table('ordenes_compras')->select('id','proveedore_id')->get();

    foreach ($orden_compras as $key => $oc) {
      $proveedor = DB::table('bancos_proveedores')
      ->where('proveedor_id',$oc->proveedore_id)->first();
      if (isset($proveedor) == true) {
      $pboc = new  \App\PartidasDatosBancariosOC();
      $pboc->banco = $proveedor->banco;
      $pboc->clabe = $proveedor->clabe;
      $pboc->cuenta = $proveedor->cuenta;
      $pboc->orden_compra_id = $oc->id;
      $pboc->save();
      }
    }

    return response()->json(['status' => true]);
   //////////////////////////////
  try {
      DB::beginTransaction();
      //35 GRUPO_ID
      $ep= DB::table('equipos_perforacion')->get();
      foreach ($ep as $key => $value) {
      /////AGREGANDO ARTICULOS
      $articulo = new \App\Articulo();
      $articulo->nombre = $value->descripcion;
      $articulo->codigo = $value->codigo;
      $articulo->descripcion = $value->descripcion.' MARCA : '.$value->marca.' MODELO : '.$value->modelo .' CODIGO : '.$value->codigo;
      $articulo->marca = $value->marca;
      $articulo->unidad = 'PZA';
      $articulo->grupo_id = 35;
      $articulo->calidad_id = 2;
      $articulo->tipo_resguardo_id = 3;
      $articulo->nombreproveedor = $value->descripcion;
      Utilidades::auditar($articulo,$articulo->id);
      $articulo->save();

      ////AGREGANDO REQUISICION HAS ORDEN DE COMPRAS PARTIDAS DE ORDENES DE COMPRAS
      $rhoc = new \App\requisicionhasordencompras();
      $rhoc->requisicione_id = 1;
      $rhoc->orden_compra_id = 1;
      $rhoc->articulo_id = $articulo->id;
      $rhoc->cantidad = 1;
      $rhoc->precio_unitario = 0;
      $rhoc->tipo_entrada = 'Interna';
      $rhoc->condicion = 0;
      $rhoc->antigua = 0;
      $rhoc->cantidad_entrada = 0;
      Utilidades::auditar($rhoc,$rhoc->id);
      $rhoc->save();

      $partidas_entradas = new \App\PartidaEntrada();
      $partidas_entradas->entrada_id = 1;
      $partidas_entradas->req_com_id = $rhoc->id;
      $partidas_entradas->articulo_id = $articulo->id;
      $partidas_entradas->validacion_calidad = 0;
      $partidas_entradas->cantidad = 1;
      $partidas_entradas->almacene_id = 1;
      $partidas_entradas->pendiente = 0;
      $partidas_entradas->status = 0;
      $partidas_entradas->precio_unitario = 0;
      $partidas_entradas->numero_serie = $value->num_serie;
      Utilidades::auditar($partidas_entradas,$partidas_entradas->id);
      $partidas_entradas->save();

      $lote = new \App\Lote();
      $lote->nombre = 'lote 0001-'.$articulo->id;
      $lote->entrada_id = $partidas_entradas->id;
      $lote->articulo_id = $articulo->id;
      $lote->cantidad = 1;
      Utilidades::auditar($lote,$lote->id);
      $lote->save();
      $lote->nombre = 'lote 0001-'.$articulo->id.'-'.$lote->id;
      $lote->save();

      // $valores_uno = substr('lote ',$lote->nombre);
      // $valores_dos = explode('-',$valores_uno);

      $lote_almacen = new \App\LoteAlmacen();
      $lote_almacen->lote_id = $lote->id;
      $lote_almacen->almacene_id = 1;
      $lote_almacen->cantidad = 1;
      $lote_almacen->stocke_id = 1;
      $lote_almacen->articulo_id = $articulo->id;
      $lote_almacen->condicion = 1;
      $lote_almacen->codigo_barras =  'MCF 0001 '.$articulo->id.' '.$lote->id;
      Utilidades::auditar($lote_almacen,$lote_almacen->id);
      $lote_almacen->save();

      $stock_articulo = new \App\StockArticulo();
      $stock_articulo->cantidad = 1;
      $stock_articulo->articulo_id = $articulo->id;
      $stock_articulo->stocke_id = 1;
      Utilidades::auditar($stock_articulo,$stock_articulo->id);
      $stock_articulo->save();

      $existencias = new \App\Existencia();
      $existencias->id_lote = $lote->id;
      $existencias->articulo_id = $articulo->id;
      $existencias->almacene_id = 1;
      $existencias->cantidad = 1;
      Utilidades::auditar($existencias,$existencias->id);
      $existencias->save();

      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $movimiento = new \App\Movimiento();
      $movimiento->cantidad = 1;
      $movimiento->fecha = $hoy;
      $movimiento->hora = $hora;
      $movimiento->tipo_movimiento = 'INV';
      $movimiento->folio = 'INV-CORTE-PERFORACION';
      $movimiento->proyecto_id =  1;
      $movimiento->lote_id =  $lote_almacen->id;
      $movimiento->stocke_id =  1;
      $movimiento->almacene_id = 1;
      $movimiento->articulo_id = $articulo->id;
      Utilidades::auditar($movimiento,$movimiento->id);
      $movimiento->save();

      DB::commit();

      }
      return response()->json(['status' => true]);
      } catch (\Exception $e) {
      Utilidades::errors($e);
      DB::rollback();
      return response()->json(['status' => false]);
    }
/////////////
    $pe = DB::table('partidas_entradas')
    ->whereIn('id',['3268','3227','3226','3224','2905','2903','2901'])->get();

    foreach ($pe as $key => $value) {
      $rc = DB::table('requisicion_has_ordencompras AS rhc')
      ->join('ordenes_compras AS oc','oc.id','rhc.orden_compra_id')
      ->join('proyectos AS p','p.id','oc.proyecto_id')
      ->join('stocks AS s','s.proyecto_id','p.id')
      ->select('s.id AS s_id','p.id AS p_id')
      ->where('rhc.id',$value->req_com_id)->first();

          $lote = new \App\Lote();
          $lote->nombre = 'lote '.$value->entrada_id.'-'.$value->articulo_id;
          $lote->entrada_id = $value->id;
          $lote->articulo_id = $value->articulo_id;
          $lote->cantidad = $value->cantidad;
          Utilidades::auditar($lote,$lote->id);
          $lote->save();
          $lote->nombre = 'lote '.$value->entrada_id.'-'.$value->articulo_id.'-'.$lote->id;
          $lote->save();

          $valores_uno = str_replace('lote ','',$lote->nombre);
          $valores_dos = explode('-',$valores_uno);

          $lote_almacen = new \App\LoteAlmacen();
          $lote_almacen->lote_id = $lote->id;
          $lote_almacen->almacene_id = $value->almacene_id;
          $lote_almacen->nivel_id = ($value->nivel_id == 'null') ? null:$value->nivel_id;
          $lote_almacen->stand_id = ($value->stand_id == 'null') ? null:$value->stand_id;
          $lote_almacen->cantidad = $value->cantidad;
          $lote_almacen->stocke_id = $rc->s_id;
          $lote_almacen->articulo_id = $value->articulo_id;
          $lote_almacen->condicion = 1;
          $lote_almacen->codigo_barras = 'MCF '.$valores_dos[0].' '.$valores_dos[1].' '.$valores_dos[2];
          Utilidades::auditar($lote_almacen,$lote_almacen->id);
          $lote_almacen->save();

          $stock_articulo = new \App\StockArticulo();
          $stock_articulo->cantidad = $value->cantidad;
          $stock_articulo->articulo_id = $value->articulo_id;
          $stock_articulo->stocke_id = $rc->s_id;
          Utilidades::auditar($stock_articulo,$stock_articulo->id);
          $stock_articulo->save();

          $existencias = new \App\Existencia();
          $existencias->id_lote = $lote->id;
          $existencias->articulo_id =  $value->articulo_id;
          $existencias->almacene_id = $value->almacene_id;
          $existencias->nivel_id = ($value->nivel_id == 'null') ? null:$value->nivel_id;
          $existencias->stand_id = ($value->stand_id == 'null') ? null:$value->stand_id;
          $existencias->cantidad = $value->cantidad;
          Utilidades::auditar($existencias,$existencias->id);
          $existencias->save();

          $hoy = date("Y-m-d");
          $hora = date("H:i:s");
          $movimiento = new \App\Movimiento();
          $movimiento->cantidad = $value->cantidad;
          $movimiento->fecha = $hoy;
          $movimiento->hora = $hora;
          $movimiento->tipo_movimiento = 'ENTRADA';
          $movimiento->folio = 'Entrada-'.$rc->p_id.$rc->s_id;
          $movimiento->proyecto_id =  $rc->p_id;
          $movimiento->lote_id =  $lote_almacen->id;
          $movimiento->stocke_id =  $rc->s_id;
          $movimiento->almacene_id = $value->almacene_id;
          $movimiento->nivel_id = ($value->nivel_id == 'null') ? null:$value->nivel_id;
          $movimiento->stand_id = ($value->stand_id == 'null') ? null:$value->stand_id;
          $movimiento->articulo_id = $value->articulo_id;
          Utilidades::auditar($movimiento,$movimiento->id);
          $movimiento->save();


    }

return response()->json(['status' => true]);

//////////
$lote_almacen = DB::table('lotes AS l')->join('lote_almacen AS la','la.lote_id','l.id')->select('la.*','l.nombre AS nombre_b')
->where('la.codigo_barras','-')->get();

foreach ($lote_almacen as $key => $value) {
if ($value->nombre_b != null || $value->nombre_b != '') {
  // code...
  $solo_numeros = str_replace('lote ','',$value->nombre_b);
  $valores = explode('-',$solo_numeros);
  $folio_codigo =  str_pad($valores[0], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[1], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[2], 4, "0", STR_PAD_LEFT);
  $codigo = 'MCF '.$folio_codigo;

  $lote_almacen = \App\LoteAlmacen::where('id',$value->id)->first();
  $lote_almacen->codigo_barras = $codigo;
  $lote_almacen->update();
//
}
//   print_r ([$codigo,$value->nombre_b]);
// break;
}

return response()->json(['status' => true]);

//////
$lote_almacen = DB::table('lote_almacen AS la')->join('lotes AS l','l.id','la.id')->select('la.*','l.nombre AS nombre_b')->where('la.codigo_barras','-')->get();
foreach ($lote_almacen as $key => $value) {

  $solo_numeros = str_replace('lote ','',$value->nombre_b);
  $valores = explode('-',$solo_numeros);
  $folio_codigo =  str_pad($valores[0], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[1], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[2], 4, "0", STR_PAD_LEFT);
  $codigo = 'MCF '.$folio_codigo;

  $lote_almacen = \App\LoteAlmacen::where('id',$value->id)->first();
  $lote_almacen->codigo_barras = $codigo;
  $lote_almacen->update();


}

return response()->json(['status' => true]);
///////////////////////

	$entradas = DB::table('entradas AS e')
	->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
	->select('oc.proyecto_id')->groupBy('oc.proyecto_id')
	->where('e.condicion','1')
	->get();

    foreach ($entradas as $key => $entrada) {

      $entra = DB::table('entradas AS e')
	->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
	->select('e.*')
	->where('oc.proyecto_id',$entrada->proyecto_id)
	->where('e.condicion','1')->orderBy('e.fecha')->get();

      foreach ($entra as $k => $v) {
        $salida = DB::table('entradas')->where('id',$v->id)->update(['formato_entrada' => str_pad(($k + 1), 4, "0", STR_PAD_LEFT)]);
      }

    }
    return response()->json(['status' => true]);
/////////////////////

  $ordencompra = DB::table('ordenes_compras')->get();
    foreach ($ordencompra as $key => $value) {
     $ordenCompra = Compras::where('id',$value->id)->first();
     $ordenCompra->total_aux = (floatval(str_replace(',','',$value->total)));
     $ordenCompra->save();
    }
    return response()->json(array('status' => true));
$sse = \App\SueldoSemanaEmpleado::get();
    foreach ($sse as $key => $value) {
      $sse_ = \App\SueldoSemanaEmpleado::where('id',$value->id)->first();
      $sse_->nombre = trim(str_replace('  ',' ',$value->nombre));
      $sse_->save();
    }
    return response()->json(array('status' => true));
//    $fac_en = FacturasEntradas::
  //  leftJoin('entradas AS e','e.id','=','facturas_entradas.entrada_id')
   // ->select('facturas_entradas.*','e.orden_compra_id')
   // ->get();
   // foreach ($fac_en as $key => $value) {
   //   $fe = FacturasEntradas::where('id',$value->id)->first();
   //   $fe->orden_compra_id = $value->orden_compra_id;
   //   $fe->save();
  //  }
  //  return response()->json(array('status' => true));
//  $c = 0;
  //  $compras = Compras::where('folio','LIKE','%OC-CONSERFLOW-020-EMBRAZORY%')->get();
  //  foreach ($compras as $key => $value) {
   //   $valores = explode('-',$value->folio);

    //    $new_folio = $valores[0].'-'.$valores[1].'-'.$valores[2].'-'.'VOPAK ATL'.'-'.$valores[4];

       // $com = Compras::where('id',$value->id)->first();
       // $com->folio = $new_folio;
       // $com->save();
       // $c ++;

    //}
    //return response()->json(array('status' => true,'c' => $c));
      $c = 0;
    $compras = Compras::where('folio','LIKE','%OC-CSCT-020-POZARICA%')->get();
    foreach ($compras as $key => $value) {
      $valores = explode('-',$value->folio);

        $new_folio = $valores[0].'-CONSERFLOW-'.$valores[2].'-'.$valores[3].'-'.$valores[4];

        $com = Compras::where('id',$value->id)->first();
        $com->folio = $new_folio;
        $com->save();
        $c ++;

    }
    return response()->json(array('status' => true,'c' => $c));

}

  public function getInicioFinSemana($week, $year)
  {
    $dto = new DateTime();
    $dto->setISODate($year, $week);
    $ret['inicio'] = $dto->format('Y-m-d');
    $dto->modify('+6 days');
    $ret['fin'] = $dto->format('Y-m-d');
    return $ret;
  }
  public function arraySinDuplicados($id)
  {



    $valores= explode('&',$id);
    $fechauno = $valores[0];
    $fechados = $valores[1];

    $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
    join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
    ->whereBetween('fecha_i',[$fechauno, $fechados])
    // ->whereIn('sueldo_semana_empleado_id',$array_sin_repetidos)
    ->select('sse.nombre','sse.id')
    // ->select(DB::raw("SUM(total) AS total"))
    ->groupBy('sse.id')
    ->get();

    $Controltiempo = Controltiempo::
    join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
    ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS nombre"))
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->groupBy('empleado_asignado_id')
    ->get();


    // $Controltiempo = Controltiempo::
    // join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
    // ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS nombre"))->groupBy('empleado_asignado_id')->get();
    //
    // $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::select('id','nombre')->get();
    $array_sin_repetidos = [];
    $array_sin_repetidos_nombre = [];
    foreach ($SueldoSemanaEmpleadoRegistros as $key => $sse) {
      $nombre = 0;
      foreach ($Controltiempo as $key => $ct) {
        if (trim($sse->nombre) === $ct->nombre) {
          $nombre ++;
        }
      }

      if ($nombre > 0) {
        $array_sin_repetidos [] = $sse->id;
        $array_sin_repetidos_nombre [] = $sse->nombre;
      }
    }

    return $array_sin_repetidos;
  }


  function get_nombre_dia($fecha){
    $fechats = strtotime($fecha); //pasamos a timestamp

    //el parametro w en la funcion date indica que queremos el dia de la semana
    //lo devuelve en numero 0 domingo, 1 lunes,....
    switch (date('w', $fechats)){
      case 0: return "Domingo"; break;
      case 1: return "Lunes"; break;
      case 2: return "Martes"; break;
      case 3: return "Miercoles"; break;
      case 4: return "Jueves"; break;
      case 5: return "Viernes"; break;
      case 6: return "Sabado"; break;
    }
  }

  public function maxValueInArray($array, $keyToSearch)
  {
      $currentMax = NULL;
      foreach($array as $arr)
      {
          foreach($arr as $key => $value)
          {
              if ($key == $keyToSearch && ($value >= $currentMax))
              {
                  $currentMax = $value;
              }
          }
      }

      return $currentMax;
  }

  public function ordenar()
  {
    if ($array == null) {
      // code...

    }
    // code...
  }

  public function facturasentradasfixed_sas($id='2020-10-11&2020-11-23&109')
  {

    $orden_compras = DB::table('ordenes_compras')->select('id','proveedore_id')->get();

    foreach ($orden_compras as $key => $oc) {
      $proveedor = DB::table('bancos_proveedores')
      ->where('proveedor_id',$oc->proveedore_id)->first();
      if (isset($proveedor) == true) {
      $pboc = new  \App\PartidasDatosBancariosOC();
      $pboc->banco = $proveedor->banco;
      $pboc->clabe = $proveedor->clabe;
      $pboc->cuenta = $proveedor->cuenta;
      $pboc->orden_compra_id = $oc->id;
      $pboc->save();
      }
    }

    return response()->json(['status' => true]);

    ///////////

    $ep= DB::table('equipos_perforacion')->get();
    foreach ($ep as $key => $value) {
    /////AGREGANDO ARTICULOS
    $art_busqueda = \App\Articulo::where('nombre',$value->descripcion)
    ->where('codigo',$value->codigo)
    ->first();
    // $articulo->nombre = $value->descripcion;
    // $articulo->codigo = $value->codigo;
    // $articulo->descripcion = $value->descripcion.' MARCA : '.$value->marca.' MODELO : '.$value->modelo .' CODIGO : '.$value->codigo;
    // $articulo->marca = $value->marca;
    // $articulo->unidad = 'PZA';
    // $articulo->grupo_id = 35;
    // $articulo->calidad_id = 2;
    // $articulo->tipo_resguardo_id = 3;
    // $articulo->nombreproveedor = $value->descripcion;
    // Utilidades::auditar($articulo,$articulo->id);
    // $articulo->save();
    // $a = isset($art_busqueda) == true ? $art_busqueda->id : 0;
    if ( isset($art_busqueda) == true) {
      // code...
    ////AGREGANDO REQUISICION HAS ORDEN DE COMPRAS PARTIDAS DE ORDENES DE COMPRAS
    $rhoc = \App\requisicionhasordencompras::where('articulo_id',$art_busqueda->id)->delete();

    $partidas_entradas = \App\PartidaEntrada::where('articulo_id',$art_busqueda->id)->delete();

    $lote = \App\Lote::where('articulo_id',$art_busqueda->id)->delete();

    $lote_almacen = \App\LoteAlmacen::where('articulo_id',$art_busqueda->id)->delete();

    $stock_articulo = \App\StockArticulo::where('articulo_id',$art_busqueda->id)->delete();

    $existencias = \App\Existencia::where('articulo_id',$art_busqueda->id)->delete();

    $movimiento = \App\Movimiento::where('tipo_movimiento','INV')->delete();

    $art = \App\Articulo::where('nombre',$value->descripcion)
    ->where('codigo',$value->codigo)
    ->where('descripcion',$value->descripcion.' MARCA : '.$value->marca.' MODELO : '.$value->modelo .' CODIGO : '.$value->codigo)->delete();
  }

  }
    return response()->json(['status' => true]);

    ////////////////////////////////////////
    DB::beginTransaction();
    $pe = DB::table('partidas_entradas')
    ->whereIn('id',['3700','3699','3698','3697','3696','3695','3694','3693','3692','3691','3690','3689','3688','3687','3686','3685','3684','3683','3682','3681','3680','3679','3678',
    '3677','3676','3675','3674','3668','3667','3666','3340','3339','3268','3227','3226','3224','2905','2903','2901','2899','2489','2268','2262','2261','2165','2155'])->get();

    foreach ($pe as $key => $value) {
      $rc = DB::table('requisicion_has_ordencompras AS rhc')
      ->join('ordenes_compras AS oc','oc.id','rhc.orden_compra_id')
      ->join('proyectos AS p','p.id','oc.proyecto_id')
      ->join('stocks AS s','s.proyecto_id','p.id')
      ->select('s.id AS s_id','p.id AS p_id')
      ->where('rhc.id',$value->req_com_id)->first();

          $lote = new \App\Lote();
          $lote->nombre = 'lote '.$value->entrada_id.'-'.$value->articulo_id;
          $lote->entrada_id = $value->id;
          $lote->articulo_id = $value->articulo_id;
          $lote->cantidad = $value->cantidad;
          Utilidades::auditar($lote,$lote->id);
          $lote->save();
          $lote->nombre = 'lote '.$value->entrada_id.'-'.$value->articulo_id.'-'.$lote->id;
          $lote->save();

          $valores_uno = str_replace('lote ','',$lote->nombre);
          $valores_dos = explode('-',$valores_uno);

          $lote_almacen = new \App\LoteAlmacen();
          $lote_almacen->lote_id = $lote->id;
          $lote_almacen->almacene_id = $value->almacene_id;
          $lote_almacen->nivel_id = ($value->nivel_id == 'null') ? null:$value->nivel_id;
          $lote_almacen->stand_id = ($value->stand_id == 'null') ? null:$request->stand_id;
          $lote_almacen->cantidad = $value->cantidad;
          $lote_almacen->stocke_id = $rc->s_id;
          $lote_almacen->articulo_id = $value->articulo_id;
          $lote_almacen->condicion = 1;
          $lote_almacen->codigo_barras = 'MCF '.$valores_dos[0].' '.$valores_dos[1].' '.$valores_dos[2];
          Utilidades::auditar($lote_almacen,$lote_almacen->id);
          $lote_almacen->save();

          $stock_articulo = new \App\StockArticulo();
          $stock_articulo->cantidad = $value->cantidad;
          $stock_articulo->articulo_id = $value->articulo_id;
          $stock_articulo->stocke_id = $rc->s_id;
          Utilidades::auditar($stock_articulo,$stock_articulo->id);
          $stock_articulo->save();

          $existencias = new \App\Existencia();
          $existencias->id_lote = $lote->id;
          $existencias->articulo_id =  $value->articulo_id;
          $existencias->almacene_id = $value->almacene_id;
          $lote_almacen->nivel_id = ($value->nivel_id == 'null') ? null:$value->nivel_id;
          $lote_almacen->stand_id = ($value->stand_id == 'null') ? null:$request->stand_id;
          $existencias->cantidad = $value->cantidad;
          Utilidades::auditar($existencias,$existencias->id);
          $existencias->save();

          $hoy = date("Y-m-d");
          $hora = date("H:i:s");
          $movimiento = new \App\Movimiento();
          $movimiento->cantidad = $value->cantidad;
          $movimiento->fecha = $hoy;
          $movimiento->hora = $hora;
          $movimiento->tipo_movimiento = 'ENTRADA';
          $movimiento->folio = 'Entrada-'.$rc->p_id.$rc->s_id;
          $movimiento->proyecto_id =  $rc->p_id;
          $movimiento->lote_id =  $lote_almacen->id;
          $movimiento->stocke_id =  $rc->s_id;
          $movimiento->almacene_id = $value->almacene_id;
          $movimiento->articulo_id = $value->articulo_id;
          Utilidades::auditar($movimiento,$movimiento->id);
          $movimiento->save();


    }

    // DB::commit();
    return true;

    ///////////////
    $nomina = [];
    $suma = [];
    $arreglo = [];
    $valores= explode('&',$id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $pro = $valores[2];

    $fechaInicio=strtotime($fecha_inicial);
    $fechaFin=strtotime($fecha_final);

    $controltiempo_empleados = DB::table('control_tiempo AS ct')
    ->select('ct.empleado_asignado_id')
    ->whereBetween('ct.fecha',[$fecha_inicial,$fecha_final])
    ->where('ct.proyecto_id','=',intval($pro))
    // ->groupBy('ct.empleado_asignado_id')
    ->get();


    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      // $arreglo_nombre_dia [] = $this->get_nombre_dia(date('Y-m-d',$i));
    }

    $arreglo = [];
    foreach ($controltiempo_empleados as $key => $value) {
      // code...
      $empleado = DB::table('empleados AS e')
      ->join('puestos AS p','p.id','e.puesto_id')
      ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado"),'p.nombre AS puesto')
      ->where('e.id',$value->empleado_asignado_id)
      ->first();

      $arreglo_uno = [];
      foreach ($arreglo_fechas_buscar as $key => $value) {

        $controltiempo = DB::table('control_tiempo AS ct')
        ->where('ct.fecha',$value)
        ->where('ct.proyecto_id',$pro)
        ->first();
        $valor = 0;

        if (isset($controltiempo) == true) {

          $valor = 8;

        }else {

          $valor = 0;

        }

        $arreglo_uno [] = $valor;
      }
      $arreglo [] = [
        'empleado' => $empleado,
        'registros' => $arreglo_uno,
      ];
    }

    return response()->json($arreglo);

    /////

    try {
      DB::beginTransaction();
      //35 GRUPO_ID
      $ep= DB::table('equipos_perforacion')->get();
      foreach ($ep as $key => $value) {
      /////AGREGANDO ARTICULOS
      $articulo = new \App\Articulo();
      $articulo->nombre = $value->descripcion;
      $articulo->codigo = $value->codigo;
      $articulo->descripcion = $value->descripcion.' MARCA : '.$value->marca.' MODELO : '.$value->modelo .' CODIGO : '.$value->codigo;
      $articulo->marca = $value->marca;
      $articulo->unidad = 'PZA';
      $articulo->grupo_id = 35;
      $articulo->calidad_id = 2;
      $articulo->tipo_resguardo_id = 3;
      $articulo->nombreproveedor = $value->descripcion;
      Utilidades::auditar($articulo,$articulo->id);
      $articulo->save();

      ////AGREGANDO REQUISICION HAS ORDEN DE COMPRAS PARTIDAS DE ORDENES DE COMPRAS
      $rhoc = new \App\requisicionhasordencompras();
      $rhoc->requisicione_id = 1;
      $rhoc->orden_compra_id = 1;
      $rhoc->articulo_id = $articulo->id;
      $rhoc->cantidad = 1;
      $rhoc->precio_unitario = 0;
      $rhoc->tipo_entrada = 'Interna';
      $rhoc->condicion = 0;
      $rhoc->antigua = 0;
      $rhoc->cantidad_entrada = 0;
      Utilidades::auditar($rhoc,$rhoc->id);
      $rhoc->save();

      $partidas_entradas = new \App\PartidaEntrada();
      $partidas_entradas->entrada_id = 1;
      $partidas_entradas->req_com_id = $rhoc->id;
      $partidas_entradas->articulo_id = $articulo->id;
      $partidas_entradas->validacion_calidad = 0;
      $partidas_entradas->cantidad = 1;
      $partidas_entradas->almacene_id = 1;
      $partidas_entradas->pendiente = 0;
      $partidas_entradas->status = 0;
      $partidas_entradas->precio_unitario = 0;
      $partidas_entradas->numero_serie = $value->num_serie;
      Utilidades::auditar($partidas_entradas,$partidas_entradas->id);
      $partidas_entradas->save();

      $lote = new \App\Lote();
      $lote->nombre = 'lote 0001-'.$articulo->id;
      $lote->entrada_id = $partidas_entradas->id;
      $lote->articulo_id = $articulo->id;
      $lote->cantidad = 1;
      Utilidades::auditar($lote,$lote->id);
      $lote->save();
      $lote->nombre = 'lote 0001-'.$articulo->id.'-'.$lote->id;
      $lote->save();

      // $valores_uno = substr('lote ',$lote->nombre);
      // $valores_dos = explode('-',$valores_uno);

      $lote_almacen = new \App\LoteAlmacen();
      $lote_almacen->lote_id = $lote->id;
      $lote_almacen->almacene_id = 1;
      $lote_almacen->cantidad = 1;
      $lote_almacen->stocke_id = 1;
      $lote_almacen->articulo_id = $articulo->id;
      $lote_almacen->condicion = 1;
      $lote_almacen->codigo_barras =  'MCF 0001 '.$articulo->id.' '.$lote->id;
      Utilidades::auditar($lote_almacen,$lote_almacen->id);
      $lote_almacen->save();

      $stock_articulo = new \App\StockArticulo();
      $stock_articulo->cantidad = 1;
      $stock_articulo->articulo_id = $articulo->id;
      $stock_articulo->stocke_id = 1;
      Utilidades::auditar($stock_articulo,$stock_articulo->id);
      $stock_articulo->save();

      $existencias = new \App\Existencia();
      $existencias->id_lote = $lote->id;
      $existencias->articulo_id = $articulo->id;
      $existencias->almacene_id = 1;
      $existencias->cantidad = 1;
      Utilidades::auditar($existencias,$existencias->id);
      $existencias->save();

      $hoy = date("Y-m-d");
      $hora = date("H:i:s");
      $movimiento = new \App\Movimiento();
      $movimiento->cantidad = 1;
      $movimiento->fecha = $hoy;
      $movimiento->hora = $hora;
      $movimiento->tipo_movimiento = 'INV';
      $movimiento->folio = 'INV-CORTE-PERFORACION';
      $movimiento->proyecto_id =  1;
      $movimiento->lote_id =  $lote_almacen->id;
      $movimiento->stocke_id =  1;
      $movimiento->almacene_id = 1;
      $movimiento->articulo_id = $articulo->id;
      Utilidades::auditar($movimiento,$movimiento->id);
      $movimiento->save();

      DB::commit();

      }
      return response()->json(['status' => true]);
      } catch (\Exception $e) {
      Utilidades::errors($e);
      DB::rollback();
      return response()->json(['status' => false]);
    }

///////////////////////

    $ep= DB::table('equipos_perforacion')->get();
    $a = [];
    foreach ($ep as $key => $value) {
    //   $articulos = DB::table('articulos')->where('descripcion','LIKE','%'.$value->descripcion.'%')->first();
    //   if (isset($articulos) == true) {
    //
    //   $a []= [
    //     'articulo' => $articulos,
    //     'equipo' => $value,
    //   ];
    // }

    }
    return response()->json($a);
    //////////////////////////////
    $detalles_viaticos = new \App\DetalleViaticoCSCT();
    $detalles_viaticos->solicitud_viaticos_id = 15;
    $detalles_viaticos->catalogo_conceptos_viaticos_id = 1;
    $detalles_viaticos->transferencia_electronica = '12.12';
    $detalles_viaticos->efectivo = 0;
    $detalles_viaticos->total = 0;
    $detalles_viaticos->save();
    return $detalles_viaticos;

    /////////////////////////////

    $d = DB::table('detalle_viaticos')->get();

    foreach ($d as $key => $value) {

      $dd = \App\DetalleViatico::where('solicitud_viaticos_id',$value->solicitud_viaticos_id)
      ->where('catalogo_conceptos_viaticos_id',$value->catalogo_conceptos_viaticos_id)
      ->update(['id' => ($key + 1)]);

      // $dd->id = $key + 1;
      // $dd->update();
    }

    return response()->json($d);

    $v =['id' => 104, 'name' => "GRECIA NARANJO VALDEZ"];
    return response()->json(gettype($v));

    $nomina = [];
    $suma = [];
    $arreglo = [];
    $valores= explode('&',$id);
    $fecha_inicial = $valores[0];
    $fecha_final = $valores[1];
    $tipo = $valores[2];

    $fechaInicio=strtotime($fecha_inicial);
    $fechaFin=strtotime($fecha_final);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      $arreglo_nombre_dia [] = $this->get_nombre_dia(date('Y-m-d',$i));
    }

    $empleados = Empleado::
    where('id_checador',$tipo)
    ->get();


    foreach ($empleados as $key => $value) {
      $asistencias = \App\Asistencia::join('registros AS r','r.id','asistencias.registro_id')
      ->leftJoin('estado_asistencia_registros AS ear','ear.id','r.estado_asistencia_id')
      ->select('r.*','ear.nombre AS nom')
      ->whereBetween('asistencias.fecha',[$fecha_inicial,$fecha_final])
      ->where('asistencias.empleado_id',$value->id)
      ->get();

      $arreglo [] = ['empleado' => $value,
                      'registros' => $asistencias];
    }
    return $arreglo;

    $tipo = '1';

    $asistencia_ = \App\Asistencia::join('registros AS r','r.id','asistencias.registro_id')
    ->join('empleados AS e','e.id','asistencias.empleado_id')
    ->select('r.*','asistencias.fecha','asistencias.empleado_id')
    ->whereBetween('asistencias.fecha',['2020-11-09','2020-11-15'])
    ->where('r.observaciones','!=','null')
    ->where('e.id_checador',$tipo)
    ->get();

    // return $asistencia_;
    //
    // $valores= explode('&',$this->id);
    // $fecha_inicial = $valores[0];
    // $fecha_final = $valores[1];

    $empleados = Empleado::
    where('id_checador',$tipo)
    ->select('id')
    ->get();
    $e_num = [];
    foreach ($empleados as $key => $value) {
      $e_num [] = $value->id;
    }


        $posicion_busqueda = array_search($asistencia_[0]->empleado_id,$e_num);

        // $pos = 0;
        //
        // if ($posicion_busqueda == 0) {
        //   $pos = 3;
        // }else {
        //   $pos = ($posicion_busqueda[0] * 2) + 3;
        // }

        return $posicion_busqueda;
/////////////////////

$lote_almacen = DB::table('lotes AS l')->join('lote_almacen AS la','la.lote_id','l.id')->select('la.*','l.nombre AS nombre_b')
->where('la.codigo_barras','-')->get();

foreach ($lote_almacen as $key => $value) {
if ($value->nombre_b != null || $value->nombre_b != '') {
  // code...
  $solo_numeros = str_replace('lote ','',$value->nombre_b);
  $valores = explode('-',$solo_numeros);
  $folio_codigo =  str_pad($valores[0], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[1], 4, "0", STR_PAD_LEFT).' '.str_pad($valores[2], 4, "0", STR_PAD_LEFT);
  $codigo = 'MCF '.$folio_codigo;

  $lote_almacen = \App\LoteAlmacen::where('id',$value->id)->first();
  $lote_almacen->codigo_barras = $codigo;
  $lote_almacen->update();
//
}
//   print_r ([$codigo,$value->nombre_b]);
// break;
}

return response()->json(['status' => true]);

//////////////////////////
    $entradas = DB::table('entradas AS e')
 ->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
 ->select('oc.proyecto_id')->groupBy('oc.proyecto_id')
 ->where('e.condicion','1')
 ->get();

foreach ($entradas as $key => $entrada) {

$entra = DB::table('entradas AS e')
 ->leftjoin('ordenes_compras AS oc','oc.id','e.orden_compra_id')
 ->select('e.*')
 ->where('oc.proyecto_id',$entrada->proyecto_id)
 ->where('e.condicion','1')->orderBy('e.fecha')->get();

foreach ($entra as $k => $v) {
 $salida = DB::table('entradas')->where('id',$v->id)->update(['formato_entrada' => str_pad(($k + 1), 4, "0", STR_PAD_LEFT)]);
}

}
return response()->json(['status' => true]);


    $salidas = DB::table('salidas')->select('proyecto_id')->groupBy('proyecto_id')->get();

    foreach ($salidas as $key => $salida) {

      $partidas = DB::table('salidas')->where('proyecto_id',$salida->proyecto_id)->orderBy('fecha')->get();

      foreach ($partidas as $k => $v) {
        $salida = DB::table('salidas')->where('id',$v->id)->update(['folio' => str_pad(($k + 1), 3, "0", STR_PAD_LEFT)]);
      }

    }
    return response()->json(['status' => true]);



    $arreglo = [];
    $proyectos = DB::table('ordenes_compras AS oc')
    ->join('proyectos AS p','p.id','oc.proyecto_id')
    ->leftJoin('proyecto_subcategorias AS ps','ps.id','p.proyecto_subcategorias_id')
    ->where('ps.proyecto_categoria_id','1')
    ->orWhere('ps.proyecto_categoria_id','2')
    ->select('oc.proyecto_id')
    ->groupBy('oc.proyecto_id')->get()->toArray();
    $a = [];
    foreach ($proyectos as $key => $value) {
      $a [] = $value->proyecto_id;
    }

    $partidas_rhoc_a = requisicionhasordencompras::
    join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
    ->join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('grupos AS g','g.id','a.grupo_id')
    ->select('a.id','g.nombre','a.descripcion')
    ->whereIn('oc.proyecto_id',$a)
    ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
    ->whereNotIn('g.nombre',['TECNOLOGÍAS DE LA INFORMACIÓN','SOFTWARE','PAPELERIA','LIMPIEZA','OFICINA'])
    ->groupBy('a.id')
    ->orderBy('g.nombre')
    ->get();

    $arreglo = [];
    foreach ($partidas_rhoc_a as $key => $value) {
        $partidas_rhoc = requisicionhasordencompras::
        select(DB::raw("SUM(cantidad) AS cantidad_t"))
        ->where('articulo_id',$value->id)
        ->first();

      $partidas_entradas = DB::table('partidas_entradas')
      ->select(DB::raw("SUM(cantidad) AS cantidad_e"))
      ->where('articulo_id',$value->id)
      ->first();

      $partidas_salidas = DB::table('partidas AS p')
      ->join('lote_almacen AS la','la.id','p.lote_id')
      ->select(DB::raw("SUM(p.cantidad) AS cantidad_s"))
      ->where('la.articulo_id',$value->id)
      ->first();

      $arreglo [] =
      [
        'grupo' => $value->nombre,
        'descripcion' => $value->descripcion,
        'cantidad_inicial' => $partidas_rhoc->cantidad_t,
        'cantidad_entrada' => $partidas_entradas->cantidad_e,
        'cantidad_salida' => $partidas_salidas->cantidad_s,
      ];

    }

    return response()->json(($arreglo));

    $partidas_rhoc_a = requisicionhasordencompras::
    join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
    ->join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
    ->select('requisicion_has_ordencompras.*','a.descripcion as description','oc.folio'
    )
    ->whereIn('oc.proyecto_id',$a)
    ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
    ->get()->toArray();

    return response()->json($partidas_rhoc_a);
    // $valores = $proe[0];
    $where = 'ordenes_compras.proyecto_id';

    $ordencompra = Compras::
    join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
    ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
    'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
    ->whereIn($where,$a)
    ->get();

    foreach ($ordencompra as $key => $value) {

      $partidas_rhoc_a = requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      ->join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
      ->select('requisicion_has_ordencompras.*','a.descripcion as description'
      )
      ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
      ->get()->toArray();

      $partidas_rhoc_s = requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      ->join('servicios AS s','s.id','=','requisicion_has_ordencompras.servicio_id')
      ->select('requisicion_has_ordencompras.*','s.nombre_servicio as description'
      )
      ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')->get()->toArray();

      $partidas_rhoc = array_merge($partidas_rhoc_a,$partidas_rhoc_s);

      $arr = [];
      foreach ($partidas_rhoc as $key => $va) {
        if ($va['articulo_id'] != '') {
          $entrada = \App\PartidaEntrada::
          leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
          ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
          ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
          ->where('partidas_entradas.req_com_id',$va['id'])
          ->where('m.tipo_movimiento','Entrada')
          ->select('m.cantidad','m.fecha','m.id','l.nombre')
          ->get();
        }
        if ($va['servicio_id'] != '') {
          $entrada = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
          ->select('servicio_check.fecha','rhoc.cantidad','servicio_check.id')
          ->where('servicio_check.rhoc_id',$va['id'])
          ->get();
        }

        if ($va['articulo_id'] != '') {
          $salida = \App\PartidaEntrada::
          leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
          ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
          ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
          ->where('partidas_entradas.req_com_id',$va['id'])
          ->where('m.tipo_movimiento','Salida')
          ->select('m.cantidad','m.fecha','m.id')
          ->get();
        }
        if ($va['servicio_id'] != '') {
          $salida = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
          ->select('servicio_check.fecha','rhoc.cantidad','servicio_check.id')
          ->where('servicio_check.rhoc_id',$va['id'])
          ->get();
        }
        $arr [] = [
          'salida' => $salida,
          'entrada' => $entrada,
          'descripcion' => $va['description'],
          'cantidad' => $va['cantidad'],
          'comentarios' => $va['comentario'],
        ];
      }




      $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
      ->where('orden_compra_id',$value->id)->first();

      $total_sin_en = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
      ->where('orden_compra_id',$value->id)
      ->where('articulo_id','!=','NULL')
      ->where('condicion','1')->first();

      $total_con_en = requisicionhasordencompras::
     select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
      ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      ->where('requisicion_has_ordencompras.condicion','0')->first();
      $total_salidas = 0;

        $partidas = Partidas::
        Join('lote_almacen AS la','la.id','=','partidas.lote_id')
        ->Join('lotes AS l','l.id','=','la.lote_id')
        ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
        ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
        ->where('RHOC.orden_compra_id',$value->id)
        ->select(DB::raw('SUM(partidas.cantidad) AS suma_total_partidas'))
        ->first();

        $total_con_en_ser = requisicionhasordencompras::
       select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en_ser'))
        ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
        ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')
        ->where('requisicion_has_ordencompras.condicion','0')->first();

        $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
        if ($total_con_en_ser->suma_total_con_en_ser != null) {
          $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
        }
      $total = $total_oc->suma_total_oc == null ? 0 : $total_oc->suma_total_oc;
      $total_con_entradas = $total_con_en->suma_total_con_en == null ? 0 : $total_con_en->suma_total_con_en;
      $total_sin_entradas = $total_sin_en->suma_total_sin_en == null ? 0 : $total_sin_en->suma_total_sin_en;


      if (($total_con_entradas) == 0) {
      $porcentaje = 0;
      }else {
      $porcentaje = (($total_con_entradas) * 100) / ($total);
      }
      if (($total_salidas) == 0) {
      $porcentaje_salida = 0;
      }else {
      $porcentaje_salida = (($total_salidas) * 100) / ($total);
      }

      $facturas_entradas = FacturasEntradas::
      where('orden_compra_id',$value->id)
      ->where('entrada_id','0')
      ->where('entrada_id','')
      ->get();

      $total_factura = 0;
      $facturas_folios = '';
      if (count($facturas_entradas) > 0) {
        foreach ($facturas_entradas as $key => $vs) {
          $total_factura += $vs->total_factura;
          $facturas_folios .= $vs->uuid. ' ';
        }
      }

      $diferencia_costos = (floatval(str_replace(',','',$value->total))) - ($total_factura);

      $pagos_compras = PagoCompra::
      join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
      ->select(DB::raw('SUM(cargo) AS total_pagos'))
      ->where('pnr.ordenes_comp_id',$value->id)->first();

      $pagos_compras_pagos = PagoCompra::
      join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
      ->select('pagos_compras.cargo','pagos_compras.tipo_cambio',
      DB::raw('(pagos_compras.cargo * pagos_compras.tipo_cambio) AS total_pesos'))
      ->where('pnr.ordenes_comp_id',$value->id)->get();

      $tipo_change = '';
      $pagos_c = '';
      $total_en_mn = 0;
      foreach ($pagos_compras_pagos as $key => $vp) {
        $tipo_change.=round($vp->tipo_cambio,2).','.PHP_EOL;
        $pagos_c.=round($vp->cargo,2).','.PHP_EOL;
        $total_en_mn += $vp->total_pesos;
      }

      $total_pagos = $pagos_compras->total_pagos == null ? 0 :$pagos_compras->total_pagos ;

      $tipo_cambio = '';

      if (($total_pagos) == 0) {
      $porcentaje_pago = 0;
      }else {
      if ($value->moneda == 1) {
        $tipo_cambio = $total_pagos / (floatval(str_replace(',','',$value->total)));
        }
      }

      if (($total_pagos) == 0) {
      $porcentaje_pago = 0;
      }else {
      $porcentaje_pago = (($total_pagos) * 100) / ((floatval(str_replace(',','',$value->total))));
      }

      $folio_oc = '';
      if ($value->folio != '') {
        $valores = explode('-',$value->folio);
        if (count($valores) == 5) {
          $folio_oc = $valores[3].'-'.$valores[4];
        }elseif(count($valores) == 6){
          $folio_oc = $valores[3].'-'.$valores[4].'-'.$valores[5];
        }
      }
      $moneda = '';
      if($value->moneda == 1){ $moneda = 'USD';}
      if($value->moneda == 2){ $moneda = 'MXN';}


      $arreglo [] = [
        'oc' => $value,
        'partidas' => $arr,
        'folio_oc' => $folio_oc,
        'total_par' => $total,
        'moneda' => $moneda,
        'total_sin_entrada' => $total_sin_entradas,
        'total_con_entrada' => $total_con_entradas,
        'procentaje_entrada' => $porcentaje,
        'total_salidas' => $total_salidas,
        'porcentaje_salidas' => $porcentaje_salida,
        'total_factura' => $total_factura,
        'factura' => $facturas_folios,
        'diferencia_costos' => $diferencia_costos,
        'totales' => $pagos_compras_pagos,
        'tipo_cambio' => $tipo_change,
        'pagos' => $pagos_c,
        'total_en_mn' => $total_en_mn,
        'porcentaje_pago' => $porcentaje_pago,
      ];
    }

    return response()->json($arreglo);




    ////////////////////////////////////////////////////////////////
    //////////////////////////////////////////////////////////////



    $suel_sema_empleado = DB::table('sueldo_semana_empleado')->where('empleado_id','0')->get();

    $arreglo = [];$case_one = null;

    foreach ($suel_sema_empleado as $key => $value) {

      $emp = [];
      if ($value->empresa === 'CSCT') {

        $emp_csct = EmpleadoDBP::get();
        foreach ($emp_csct as $k => $a) {

          $nombre = $a->nombre;
          $ap_paterno = $a->ap_paterno;
          $ap_materno = $a->ap_materno;

          $case_one = str_replace(' ','',$ap_paterno).str_replace(' ','',$ap_materno).str_replace(' ','',$nombre);

          if(str_replace(' ','',$value->nombre) === $case_one)

          // $emp [] = $a->id;
          {
          $s_s_e = SueldoSemanaEmpleado::where('id',$value->id)->first();
          $s_s_e->empleado_id = $a->id;
          $s_s_e->save();
          }

        }


      }elseif ($value->empresa === 'CONSERFLOW') {
        $emp_con = Empleado::get();
        foreach ($emp_con as $k => $b) {

          $nombre = $b->nombre;
          $ap_paterno = $b->ap_paterno;
          $ap_materno = $b->ap_materno;

          $case_one = str_replace(' ','',$ap_paterno).str_replace(' ','',$ap_materno).str_replace(' ','',$nombre);

          if(str_replace(' ','',$value->nombre) === $case_one)
          {
          $s_s_e = SueldoSemanaEmpleado::where('id',$value->id)->first();
          $s_s_e->empleado_id = $a->id;
          $s_s_e->save();
          }

        }
      }

    }

    return response()->json($suel_sema_empleado);


    $pr = DB::table('partidas_requisiciones AS pr')
    ->join('requisiciones AS r','r.id','pr.requisicione_id')
    ->join('empleados AS e','e.id','r.solicita_empleado_id')
    ->select('r.folio','r.descripcion_uso','r.fecha_solicitud',
    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
    'pr.requisicione_id','pr.articulo_id','pr.servicio_id','pr.cantidad','pr.cantidad_almacen','pr.comentario')
    ->where('r.proyecto_id',$id)
    ->get();
    $arreglo = [];
    foreach ($pr as $key => $value) {
      $a = null;$s=null;
      if ($value->articulo_id != null) {
        $a = DB::table('articulos AS a')->select('a.descripcion','a.unidad')->where('id',$value->articulo_id)->first();
      }
      if ($value->servicio_id != null) {
        $s = DB::table('servicios AS s')->select('s.nombre_servicio','s.unidad_medida')->where('id',$value->servicio_id)->first();
      }
      $oc = DB::table('requisicion_has_ordencompras AS rhoc')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->select('rhoc.id','oc.folio','rhoc.articulo_id','rhoc.cantidad','oc.periodo_entrega','oc.moneda',
      'rhoc.precio_unitario')
      ->where('rhoc.requisicione_id',$value->requisicione_id)
      ->where('rhoc.articulo_id',$value->articulo_id)
      ->where('rhoc.servicio_id',$value->servicio_id)
      ->first();

      $pe = [];
      if (isset($oc) == true) {
      $pe = DB::table('partidas_entradas AS pe')
      ->join('entradas AS e','e.id','pe.entrada_id')
      ->where('req_com_id',$oc->id)
      ->select('pe.cantidad','e.fecha')
      ->get();
      }

      $arreglo [] = [
        'requis' => $value,
        'oc' => $oc,
        's' => $s,
        'a' => $a,
        'e' => count($pe),
      ];
    }

    return response()->json($arreglo);

    $rhoc = DB::table('requisicion_has_ordencompras AS rhc')
    ->join('ordenes_compras AS oc','oc.id','=','rhc.orden_compra_id')
    ->select('rhc.*')
    ->where('oc.proyecto_id',$id)
    ->get();
    $oc = 0;$e = 0; $s=0;
    foreach ($rhoc as $key => $value) {
      // code...
      $salidas = DB::table('partidas AS p')
      ->leftJoin('lote_almacen AS la','la.id','p.lote_id')
      ->leftJoin('lotes AS l','l.id','la.lote_id')
      ->leftJoin('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->where('req_com_id',$value->id)
      ->select(DB::raw("SUM(p.cantidad) as cantidad_salida"))
      ->first();

      $oc += $value->cantidad;
      $e += $value->cantidad_entrada;
      $s += (isset($salidas) == true) ? $salidas->cantidad_salida : 0;

    }
    $arreglo = [$oc,$e,$s];
    return response()->json($arreglo);

    $valores = explode('&',$id);
    $proveedores_oc = DB::table('proveedores AS p')
    ->join('ordenes_compras AS oc','oc.proveedore_id','=','p.id')
    ->select('p.id')->where('oc.proyecto_id',$valores[0])
    ->groupBy('p.id')->get();

    $arreglo_pro_pro = [];
    foreach ($proveedores_oc as $ks => $vs) {

      $oc_u = DB::table('ordenes_compras AS oc')
      ->select(DB::raw("SUM(oc.total_aux) as total_pro"))
      ->where('oc.proveedore_id',$vs->id)
      ->where('oc.proyecto_id',$valores[0])
      ->where('oc.moneda','2')
      ->first();

      $oc_m = DB::table('ordenes_compras AS oc')
      ->select(DB::raw("SUM(oc.total_aux) as total_pro"))
      ->where('oc.proveedore_id',$vs->id)
      ->where('oc.proyecto_id',$valores[0])
      ->where('oc.moneda','1')
      ->first();


      $proveedor = DB::table('proveedores AS p')->where('id',$vs->id)->first();

      $arreglo_pro_pro [] = [
        'proveedor' => $proveedor->razon_social,
        'total_mnx' => $oc_m->total_pro + ($oc_u->total_pro * $valores[1]),
      ];
    }
    foreach ($arreglo_pro_pro as $ka => $a) {
      $orden_uno[$ka] = $a['total_mnx'];
    }
    array_multisort($orden_uno,SORT_DESC,$arreglo_pro_pro);
    return response()->json($arreglo_pro_pro);

    $ordencompra = DB::table('ordenes_compras')->where('proyecto_id',$id)->get();
    foreach ($ordencompra as $key => $value) {
     $ordenCompra = Compras::where('id',$value->id)->first();
     $ordenCompra->total_aux = (floatval(str_replace(',','',$value->total)));
     $ordenCompra->save();
    }
    return response()->json(array('status' => true));
    $arreglo = [];

    $requiciones = DB::table('requisiciones')->where('proyecto_id',$id)
    ->where('folio','NOT LIKE','%-SR')
    ->get();

    foreach ($requiciones as $key => $value) {

      $partidaRe = DB::table('partidas_requisiciones')
      ->leftJoin('articulos AS a','a.id','partidas_requisiciones.articulo_id')
      ->leftJoin('servicios AS s','s.id','partidas_requisiciones.servicio_id')
      ->select('partidas_requisiciones.*','a.descripcion','s.nombre_servicio','a.unidad','s.unidad_medida')
      ->where('partidas_requisiciones.requisicione_id',$value->id)->get();

      $arreglo_dos = [];
      foreach ($partidaRe as $k => $value_dos) {

        $requisicionhasordencompras = DB::table('requisicion_has_ordencompras')
        ->select(
        DB::raw("SUM(requisicion_has_ordencompras.cantidad) AS cantidad_comprada")
        )
        ->where('requisicione_id',$value_dos->requisicione_id)
        ->where('articulo_id',$value_dos->articulo_id)
        ->where('servicio_id',$value_dos->servicio_id)
        ->first();
        $requisicionhasordencompraspartidas = DB::table('requisicion_has_ordencompras')
        ->where('requisicione_id',$value_dos->requisicione_id)
        ->where('articulo_id',$value_dos->articulo_id)
        ->where('servicio_id',$value_dos->servicio_id)
        ->first();

        $arreglo_dos [] = [
          'partidas' => $value_dos,
          'compras_partidas' => $requisicionhasordencompras,
          'partidas_compras' =>$requisicionhasordencompraspartidas,
        ];
      }

      $arreglo [] =[
        'requisicion' => $value,
        'partidas' => $arreglo_dos,
      ];
    }
    return response()->json($arreglo);

//////////////

    $fechauno = '2020-03-30';
    $fechados = '2020-04-05';

    $SSER = \App\SueldoSemanaEmpleadoRegistros::
    join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
    ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$fechauno, $fechados])
    ->select('sse.nombre')
    ->groupBy('sse.nombre')
    ->get();
    $arreglo_sin_p = [];

    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
      $arreglo_fechas_buscar [] = date('Y-m-d',$i);
    }
    foreach ($SSER as $key => $sser_) {

      $sueldo_periodo = \App\SueldoSemanaEmpleadoRegistros::
        join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
      ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$fechauno, $fechados])
      ->where('nombre',$sser_->nombre)
      ->select(DB::raw("SUM(total) AS total"))
      ->first();


      $empleado = \App\Empleado::
      where(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre)"),'LIKE','%'.trim(str_replace('  ',' ',$sser_->nombre)).'%')
      ->select('id','nombre','ap_paterno','ap_materno')
      ->first();

      $data = [];
      $contador = 0;
      $total = 0;
      $total_ema_final = 0;
      $total_ema_final_csct = 0;
      $total_eba_final = 0;
      $total_eba_final_csct = 0;
      foreach ($arreglo_fechas_buscar as $key => $valor_fecha) {

        if (isset($empleado) == true) {

        $ct = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->where('fecha',$valor_fecha)
        ->where('control_tiempo.empleado_asignado_id',$empleado->id)
        ->select('control_tiempo.id')
        ->first();

        $fechas_final = substr($valor_fecha,5,2);

        if (isset($ct) == true) {
          if (date('l',strtotime($valor_fecha)) != 'Sunday') {
            $contador ++;
          }
          $data [] = ['fecha' => $valor_fecha, 'data' => $ct,'total' => ($sueldo_periodo->total)];
        }else {
          if (date('l',strtotime($valor_fecha)) != 'Sunday') {
            $total += $sueldo_periodo->total/6;

                  $ema = \App\CoutaImssEMA::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
                  ->where('mes',$fechas_final)
                  ->where('anio',date('Y'))
                  ->select(DB::raw("SUM(subtotal) AS subtotal"),DB::raw("SUM(dias) AS dias"))
                  ->first();

                  if (isset($ema) == true) {
                      if ($ema->subtotal != 0) {
                        $total_ema_dia =  $ema->subtotal/$ema->dias;
                        $total_ema_final = $total_ema_dia;
                      }
                  }

                  $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
                  ->where('mes',$fechas_final)
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->first();

                  if (isset($ema_c_csct) == true) {
                    if ($ema_c_csct->subtotal != 0) {
                      $total_ema_dia_c_csct =  $ema_c_csct->subtotal/$ema_c_csct->dias;
                      $total_ema_final_csct = $total_ema_dia_c_csct;
                    }
                  }

                  $eba = \App\CoutaImssEBA::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
                  ->where('meses','like','%'.$fechas_final.'%')
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->first();

                  if (isset($eba) == true) {
                    if ($eba->subtotal != 0 ) {
                      $total_eba_dia =  $eba->subtotal/$eba->dias;
                      $total_eba_final = $total_eba_dia;
                    }
                  }

                  $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$empleado->ap_paterno.' '.$empleado->ap_materno.' '.$empleado->nombre)
                  ->where('meses','like','%'.$fechas_final.'%')
                  ->where('anio',date('Y'))
                  ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                  ->first();
                  if (isset($eba_c_csct) == true) {
                    if ($eba_c_csct->subtotal != 0 ) {
                      $total_eba_dia_c_csct =  $eba_c_csct->subtotal/$eba_c_csct->dias;
                      $total_eba_final_csct = $total_eba_dia_c_csct;
                    }
                  }

          $data [] = ['fecha' => $valor_fecha, 'data' => null,'total' => ($sueldo_periodo->total),
          'ema' => $total_ema_final, 'ema_csct' => $total_ema_final_csct,
          'eba' => $total_eba_final, 'eba_csct' => $total_eba_final_csct];
          }else {
            $data [] = ['fecha' => $valor_fecha, 'data' => null,'total' => null];
          }
        }
        }
      }
      if($contador <= 5){
        $arreglo_sin_p [] = ['e' => trim(str_replace('  ','',$sser_->nombre)),
        'em  ' => $empleado,'ct' => $data,'contador' => $contador,'total' => $total];
      }
    }
    return response()->json($arreglo_sin_p);

    $partida_entrada = DB::table('partidas_entradas')
    //Datos pertenecientes a partidas entrdas
    ->join('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    ->join('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    ->leftJoin('grupos AS G','G.id','=','A.grupo_id')
    ->join('categorias AS CT','CT.id','=','G.categoria_id')
    ->join('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    ->join('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    ->join('proyectos AS PRO', 'PRO.id', '=', 'OC.proyecto_id')
    ->join('empleados AS EM','EM.id','=','E.empleado_calidad_id')
    ->join('proveedores AS p','p.id','=','OC.proveedore_id')
    ->select('partidas_entradas.*','A.unidad AS aunidad','A.id AS aid','A.descripcion AS ad',
    'PRO.nombre_corto AS proyecto', 'A.marca AS amarca','OC.id AS idcompra','OC.folio AS foliocompra',
    'RHC.cantidad AS cantidadcompra','E.id AS entradaid','E.fecha AS fechaentrada','p.razon_social AS razon_social',
    'CT.nombre AS categoria',
    DB::raw("CONCAT(EM.nombre,' ',EM.ap_paterno,' ',EM.ap_materno) AS nombree"))
    ->where('partidas_entradas.entrada_id', '=', '2')
    ->where('partidas_entradas.validacion_calidad','!=','1')->get();

    return response()->json($partida_entrada);
    ///////////////////////////
    $uno = '2 Q ENERO';
    $dos = '2 Q AGOSTO';

    $meses = [' Q ENERO',' Q FEBRERO',' Q MARZO',' Q ABRIL',' Q MAYO',
    ' Q JUNIO',' Q JULIO',' Q AGOSTO',' Q SEP.',' Q OCT.',' Q NOV.',' Q DIC.'];
    $numero = ['1 Q ','2 Q '];
    $meses_arreglo = ['ENERO','FEBRERO','MARZO','ABRIL','MAYO','JUNIO','JULIO','AGOSTO','SEP.','OCT.','NOV.','DIC.'];

    $numero_uno = str_replace($meses,"",$uno);
    $numero_dos = str_replace($meses,"",$dos);
    $mes_uno = str_replace($numero,"",$uno);
    $mes_dos = str_replace($numero,"",$dos);

    $meses_busqueda = array_search($mes_dos,$meses_arreglo);
    $meses_busqueda_inicio = array_search($mes_uno,$meses_arreglo);

    $arreglo_rango = [];
    if ($numero_uno == 1) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda_inicio];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
    }elseif ($numero_uno == 2) {
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda_inicio];
    }
    for ($i=$meses_busqueda_inicio + 1; $i < $meses_busqueda; $i++) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$i];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$i];
    }
    if ($numero_dos == 1) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
    }elseif ($numero_dos == 2) {
      $arreglo_rango [] = '1 Q '.$meses_arreglo[$meses_busqueda];
      $arreglo_rango [] = '2 Q '.$meses_arreglo[$meses_busqueda];
    }

    return response()->json($arreglo_rango);



    // code...
    $valores= explode('&',$id);
    $fechauno = $valores[0];
    $fechados = $valores[1];

    $fechas = [];
    $fechas_siete = [];
    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);

    for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
      $fechas_siete [] = date("Y-m-d", $i);
    }
    for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
      $fechas_n [] = date("Y-m-d", $i);
    }

    return response()->json($fechas_siete);

    $a = [];
    $user = Auth::user();
    $tam = count($fechas_siete);
    $proyectos = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('control_tiempo.supervisor_id',$user->empleado_id)
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('control_tiempo.proyecto_id')
    ->get();

    $arreglo_proyectos = [];
    foreach ($proyectos as $key => $proyecto) {
      $empleados_p = DB::table('control_tiempo')
      ->whereBetween('fecha',[$fechauno, $fechados])
      // ->where('control_tiempo.supervisor_id',$user->empleado_id)
      ->where('proyecto_id',$proyecto->proyecto_id)->get();

      $total = 0;

      foreach ($empleados_p as $key => $empleado) {
        $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
        if (count($contratos) > 0) {
          $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
          if (count($sueldos) > 0) {
            $total += $sueldos[0]->sueldo_diario_real;
          }
          $total += 0;
        }else {
          $total += 0;
        }
      }
      $arreglo_proyectos [] =
      [
        'proyecto' => $proyecto,
        'total' => $total,
      ];
    }
    $emplados = DB::table('control_tiempo')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('supervisor_id',$user->empleado_id)
    ->select('empleado_asignado_id')
    ->groupBy('empleado_asignado_id')
    ->get();

    for ($h=0; $h < $tam ; $h++) {
      $arreglo =[];


      $emplado_supervisor = DB::table('empleados AS e')->
      select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
      ->where('id',$user->empleado_id)
      ->first();

      $arreglo_fechas_mostrar = [];
      $arreglo_fechas_buscar = [];
      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);

      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $arreglo_fechas [] = date("d-M-yy",$i);
        $arreglo_fechas_buscar [] = date('Y-m-d',$i);
        // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
      }

      $dia   = substr($fechas_siete[$h],8,2);
      $mes = substr($fechas_siete[$h],5,2);
      $anio = substr($fechas_siete[$h],0,4);
      $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

      $a [] = [
        'ns' => $semana,
        'i' => $fechas_siete[$h],
        'f' => $fechas_n[$h],
      ];


      //
    }

    $proyectos = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('proyecto_id')
    ->get();
    $arreglos_final = [];
    foreach ($proyectos as $key => $proyecto) {
      $apariciones = [];

      $total_s = 0;
      $total_q = [];
      foreach ($a as $key => $value) {
        $empleados_tiempo = DB::table('control_tiempo')
        ->whereBetween('fecha',[$value['i'], $value['f']])
        ->where('proyecto_id',$proyecto->proyecto_id)
        ->select('empleado_asignado_id','fecha')
        ->get();
        $f = [];
        foreach ($empleados_tiempo as $key => $vs) {
          $total_s = 0;

          $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
          ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

          $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

          foreach ($SueldoSemanaEmpleado as $key => $value_s) {
            $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
            ->whereIn('sueldo_semana_empleado_id',$value_s)
            ->first();

            if (isset($SueldoSemanaEmpleadoRegistros) == true) {
              $total_s += (($SueldoSemanaEmpleadoRegistros->total*7)/6);
            }
          }
          // $contratos = DB::table('control_tiempo')
          // ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
          // ->select('c.*')
          // // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
          // ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
          // ->orderBy('id', 'desc')->get();
          // if (count($contratos) > 0) {
          //   $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
          //   if (count($sueldos) > 0) {
          //     $total_s += $sueldos[0]->sueldo_diario_real;
          //   }else {
          //     $total_s += 0;
          //   }
          // }else {
          //   $total_s += 0;
          // }

          $f [] = $total_s;
        }



        $apariciones []=array_sum($f);
        // $apariciones []= $total_s * count($empleados_tiempo);

        $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
        join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
        ->whereBetween('sueldo_quincena_empleado_registros.fecha_i',[$value['i'], $value['f']])
        ->select('sse.nombre AS nomvre','sueldo_quincena_empleado_registros.total','sueldo_quincena_empleado_registros.fecha_i')
        ->get();

        $tot_e_sq= 0;
        foreach ($SueldoQuincenaEmpleadoRegistros as $k => $sqr) {

          $total_ema_final = 0;
          $total_ema_final_csct = 0;
          $total_eba_final = 0;
          $total_eba_final_csct = 0;
          $total_gastos_e_ = 0;


          $fechas_final = substr($sqr->fecha_i,5,2);

                $ema = \App\CoutaImssEMA::where('nombre',$sqr->nomvre)
                ->where('mes',$fechas_final)
                ->where('anio',date('Y'))
                ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                ->get();
                if (count($ema) > 0) {
                  if ($ema[0]->subtotal != 0 ) {
                    $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
                    $total_ema_final += $total_ema_dia;
                  }
                }
                $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$sqr->nomvre)
                ->where('mes',$fechas_final)
                ->where('anio',date('Y'))
                ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                ->get();
                if (count($ema_c_csct) > 0) {
                  if ($ema_c_csct[0]->subtotal != 0 ) {
                    $total_ema_dia_c_csct =  $ema_c_csct[0]->subtotal/$ema_c_csct[0]->dias;
                    $total_ema_final_csct += $total_ema_dia_c_csct;
                  }
                }
                $eba = \App\CoutaImssEBA::where('nombre',$sqr->nomvre)
                // ->whereIn('mes',$fechas_final)
                ->where('anio',date('Y'))
                ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                ->get();
                if (count($eba) > 0) {
                  if ($eba[0]->subtotal != 0 ) {
                    $total_eba_dia =  $eba[0]->subtotal/$eba[0]->dias;
                    $total_eba_final += $total_eba_dia;
                  }
                }
                $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$sqr->nomvre)
                // ->whereIn('mes',$fechas_final)
                ->where('anio',date('Y'))
                ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                ->get();
                if (count($eba_c_csct) > 0) {
                  if ($eba_c_csct[0]->subtotal != 0 ) {
                    $total_eba_dia_c_csct =  $eba_c_csct[0]->subtotal/$eba_c_csct[0]->dias;
                    $total_eba_final_csct += $total_eba_dia_c_csct;
                  }
                }

                $tot_e_sq += ($sqr->total) + $total_ema_final + $total_ema_final_csct + $total_eba_final + $total_eba_final_csct;
        }


        $total_q []= $tot_e_sq;
      }


      $arreglos_final [] = [
        'proyectos' => $proyecto,
        'apa' => $apariciones,
        'q' => $total_q,
      ];
    }

    $arreglos_final_data = $this->arreglos_final_d($a);
    return response()->json($arreglos_final_data);
  }

  public function arreglos_final_d($a )
  {
    $apariciones = [];
    $total_s = 0;
    foreach ($a as $key => $value) {
      $empleados_tiempo = DB::table('control_tiempo')
      ->whereBetween('fecha',[$value['i'], $value['f']])
      ->select('empleado_asignado_id','fecha')
      ->get();
      $f = [];
      foreach ($empleados_tiempo as $key => $vs) {
        $total_s = 0;

        $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
        ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

        $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

        foreach ($SueldoSemanaEmpleado as $key => $value_s) {
          $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
          ->whereIn('sueldo_semana_empleado_id',$value_s)
          ->first();

          if (isset($SueldoSemanaEmpleadoRegistros) == true) {
            $total_s += (($SueldoSemanaEmpleadoRegistros->total*7)/6);
          }
        }
        $f [] = $total_s;
      }
      $apariciones []=array_sum($f);
    }
    return $apariciones;
  }
  public function facturasentradasfixed_dos($id ='134')
  {


    //////////////////////////////////////////////////////////////////
    $compras_pro = DB::table('ordenes_compras AS oc')->where('proveedore_id',$id)
    ->select('oc.folio','oc.fecha_orden AS fecha','oc.total',DB::raw("'compra' AS tipo"))->get()->toArray();

    $pagos = DB::table('pagos_no_recurrentes AS pnr')
    ->Join('pagos_compras AS pc','pc.pagos_no_recurrentes_id','=','pnr.id')
    ->Join('ordenes_compras AS oc','oc.id','=','pnr.ordenes_comp_id')
    ->select('pc.descripcion AS folio','pc.fecha','pc.cargo AS total',DB::raw("'pago' AS tipo"))
    ->where('oc.proveedore_id',$id)
    ->get()->toArray();

    $final = array_merge($compras_pro,$pagos);

    $fecha = array_column($final,'fecha');

    array_multisort($fecha, SORT_ASC, $final);

    $arreglo = [];
    $total = 0;
    foreach ($final as $key => $value) {
      if ($value->tipo === 'compra') {
        $total -= (floatval(str_replace(',','',$value->total)));
      }elseif ($value->tipo === 'pago') {
        $total += (floatval(str_replace(',','',$value->total)));
      }

      $arreglo [] = ['ocp' => $value,'saldo' => $total];
    }


      return response()->json($arreglo);

      ///////////////////////////////////////////////////////////////////////////////

        $valores= explode('&',$id);
        $fechauno = $valores[0];
        $fechados = $valores[1];

        $fechas = [];
        $fechas_siete = [];
        $fechaInicio=strtotime($fechauno);
        $fechaFin=strtotime($fechados);
        // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        //  $fechas [] = date("d-m-Y", $i);
        // }
        for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
          $fechas_siete [] = date("Y-m-d", $i);
        }
        for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
          $fechas_n [] = date("Y-m-d", $i);
        }
        // return response()->json(count($fechas_n));

        $a = [];
        $user = Auth::user();
        $tam = count($fechas_siete);
        $proyectos = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->whereBetween('fecha',[$fechauno, $fechados])
        // ->where('control_tiempo.supervisor_id',$user->empleado_id)
        ->select('control_tiempo.proyecto_id','p.nombre_corto')
        ->groupBy('control_tiempo.proyecto_id')
        ->get();

        $arreglo_proyectos = [];
        foreach ($proyectos as $key => $proyecto) {
          $empleados_p = DB::table('control_tiempo')
          ->whereBetween('fecha',[$fechauno, $fechados])
          // ->where('control_tiempo.supervisor_id',$user->empleado_id)
          ->where('proyecto_id',$proyecto->proyecto_id)->get();

          $total = 0;

          foreach ($empleados_p as $key => $empleado) {
            $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
            if (count($contratos) > 0) {
              $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
              if (count($sueldos) > 0) {
                $total += $sueldos[0]->sueldo_diario_real;
              }
              $total += 0;
            }else {
              $total += 0;
            }
          }
          $arreglo_proyectos [] =
          [
            'proyecto' => $proyecto,
            'total' => $total,
          ];
        }
        $emplados = DB::table('control_tiempo')
        ->whereBetween('fecha',[$fechauno, $fechados])
        // ->where('supervisor_id',$user->empleado_id)
        ->select('empleado_asignado_id')
        ->groupBy('empleado_asignado_id')
        ->get();

        for ($h=0; $h < $tam ; $h++) {
          $arreglo =[];


          $emplado_supervisor = DB::table('empleados AS e')->
          select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
          ->where('id',$user->empleado_id)
          ->first();

          $arreglo_fechas_mostrar = [];
          $arreglo_fechas_buscar = [];
          $fechaInicio=strtotime($fechauno);
          $fechaFin=strtotime($fechados);

          for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
            $arreglo_fechas [] = date("d-M-yy",$i);
            $arreglo_fechas_buscar [] = date('Y-m-d',$i);
            // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
          }

          $dia   = substr($fechas_siete[$h],8,2);
          $mes = substr($fechas_siete[$h],5,2);
          $anio = substr($fechas_siete[$h],0,4);
          $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

          $a [] = [
            'ns' => $semana,
            'i' => $fechas_siete[$h],
            'f' => $fechas_n[$h],
          ];


          //
        }

        $proyectos = DB::table('control_tiempo')
        ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
        ->whereBetween('fecha',[$fechauno, $fechados])
        ->select('control_tiempo.proyecto_id','p.nombre_corto')
        ->groupBy('proyecto_id')
        ->get();
        $arreglos_final = [];
        foreach ($proyectos as $key => $proyecto) {
          $apariciones = [];

          $total_s = 0;
          $total_q = [];
          foreach ($a as $key => $value) {
            $empleados_tiempo = DB::table('control_tiempo')
            ->whereBetween('fecha',[$value['i'], $value['f']])
            ->where('proyecto_id',$proyecto->proyecto_id)
            ->select('empleado_asignado_id','fecha')
            ->get();
            $f = [];
            foreach ($empleados_tiempo as $key => $vs) {
              $total_s = 0;

              $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
              ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

              $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

              foreach ($SueldoSemanaEmpleado as $key => $value_s) {
                $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
                ->whereIn('sueldo_semana_empleado_id',$value_s)
                ->first();

                if (isset($SueldoSemanaEmpleadoRegistros) == true) {
                  $total_s += (($SueldoSemanaEmpleadoRegistros->total*7)/6);
                }
              }
              // $contratos = DB::table('control_tiempo')
              // ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
              // ->select('c.*')
              // // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
              // ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
              // ->orderBy('id', 'desc')->get();
              // if (count($contratos) > 0) {
              //   $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
              //   if (count($sueldos) > 0) {
              //     $total_s += $sueldos[0]->sueldo_diario_real;
              //   }else {
              //     $total_s += 0;
              //   }
              // }else {
              //   $total_s += 0;
              // }

              $f [] = $total_s;
            }



            $apariciones []=array_sum($f);
            // $apariciones []= $total_s * count($empleados_tiempo);

            $SueldoQuincenaEmpleadoRegistros = \App\SueldoQuincenaEmpleadoRegistros::
            join('sueldo_quincena_empleado AS sse','sse.id','=','sueldo_quincena_empleado_registros.sueldo_quincena_empleado_id')
            ->whereBetween('sueldo_quincena_empleado_registros.fecha_i',[$value['i'], $value['f']])
            ->select('sse.nombre AS nomvre','sueldo_quincena_empleado_registros.total','sueldo_quincena_empleado_registros.fecha_i')
            ->get();

            $tot_e_sq= 0;
            foreach ($SueldoQuincenaEmpleadoRegistros as $k => $sqr) {

              $total_ema_final = 0;
              $total_ema_final_csct = 0;
              $total_eba_final = 0;
              $total_eba_final_csct = 0;
              $total_gastos_e_ = 0;


              $fechas_final = substr($sqr->fecha_i,5,2);

                    $ema = \App\CoutaImssEMA::where('nombre',$sqr->nomvre)
                    ->where('mes',$fechas_final)
                    ->where('anio',date('Y'))
                    ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                    ->get();
                    if (count($ema) > 0) {
                      if ($ema[0]->subtotal != 0 ) {
                        $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
                        $total_ema_final += $total_ema_dia;
                      }
                    }
                    $ema_c_csct = \App\CoutaImssEMACSCT::where('nombre',$sqr->nomvre)
                    ->where('mes',$fechas_final)
                    ->where('anio',date('Y'))
                    ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                    ->get();
                    if (count($ema_c_csct) > 0) {
                      if ($ema_c_csct[0]->subtotal != 0 ) {
                        $total_ema_dia_c_csct =  $ema_c_csct[0]->subtotal/$ema_c_csct[0]->dias;
                        $total_ema_final_csct += $total_ema_dia_c_csct;
                      }
                    }
                    $eba = \App\CoutaImssEBA::where('nombre',$sqr->nomvre)
                    // ->whereIn('mes',$fechas_final)
                    ->where('anio',date('Y'))
                    ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                    ->get();
                    if (count($eba) > 0) {
                      if ($eba[0]->subtotal != 0 ) {
                        $total_eba_dia =  $eba[0]->subtotal/$eba[0]->dias;
                        $total_eba_final += $total_eba_dia;
                      }
                    }
                    $eba_c_csct = \App\CoutaImssEBACSCT::where('nombre',$sqr->nomvre)
                    // ->whereIn('mes',$fechas_final)
                    ->where('anio',date('Y'))
                    ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
                    ->get();
                    if (count($eba_c_csct) > 0) {
                      if ($eba_c_csct[0]->subtotal != 0 ) {
                        $total_eba_dia_c_csct =  $eba_c_csct[0]->subtotal/$eba_c_csct[0]->dias;
                        $total_eba_final_csct += $total_eba_dia_c_csct;
                      }
                    }

                    $tot_e_sq += ($sqr->total) + $total_ema_final + $total_ema_final_csct + $total_eba_final + $total_eba_final_csct;
            }


            $total_q []= $tot_e_sq;
          }


          $arreglos_final [] = [
            'proyectos' => $proyecto,
            'apa' => $apariciones,
            'q' => $total_q,
          ];
        }

        return response()->json($arreglos_final);

    ////////////////////////////////////////77


    $valores= explode('&',$id);
    $fechauno = $valores[0];
    $fechados = $valores[1];

    $fechas = [];
    $fechas_siete = [];
    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);
    // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    //  $fechas [] = date("d-m-Y", $i);
    // }
    for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
      $fechas_siete [] = date("Y-m-d", $i);
    }
    for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
      $fechas_n [] = date("Y-m-d", $i);
    }
    // return response()->json(count($fechas_n));

    $a = [];
    $user = Auth::user();
    $tam = count($fechas_siete);
    $proyectos = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('control_tiempo.supervisor_id',$user->empleado_id)
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('control_tiempo.proyecto_id')
    ->get();

    $arreglo_proyectos = [];
    foreach ($proyectos as $key => $proyecto) {
      $empleados_p = DB::table('control_tiempo')
      ->whereBetween('fecha',[$fechauno, $fechados])
      // ->where('control_tiempo.supervisor_id',$user->empleado_id)
      ->where('proyecto_id',$proyecto->proyecto_id)->get();

      $total = 0;

      foreach ($empleados_p as $key => $empleado) {
        $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
        if (count($contratos) > 0) {
          $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
          if (count($sueldos) > 0) {
            $total += $sueldos[0]->sueldo_diario_real;
          }
          $total += 0;
        }else {
          $total += 0;
        }
      }
      $arreglo_proyectos [] =
      [
        'proyecto' => $proyecto,
        'total' => $total,
      ];
    }
    $emplados = DB::table('control_tiempo')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('supervisor_id',$user->empleado_id)
    ->select('empleado_asignado_id')
    ->groupBy('empleado_asignado_id')
    ->get();

    for ($h=0; $h < $tam ; $h++) {
      $arreglo =[];


      $emplado_supervisor = DB::table('empleados AS e')->
      select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
      ->where('id',$user->empleado_id)
      ->first();

      $arreglo_fechas_mostrar = [];
      $arreglo_fechas_buscar = [];
      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);

      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $arreglo_fechas [] = date("d-M-yy",$i);
        $arreglo_fechas_buscar [] = date('Y-m-d',$i);
        // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
      }

      $dia   = substr($fechas_siete[$h],8,2);
      $mes = substr($fechas_siete[$h],5,2);
      $anio = substr($fechas_siete[$h],0,4);
      $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

      $a [] = [
        'ns' => $semana,
        'i' => $fechas_siete[$h],
        'f' => $fechas_n[$h],
      ];


      //
    }

    $proyectos = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('proyecto_id')
    ->get();
    $arreglos_final = [];
    foreach ($proyectos as $key => $proyecto) {
      $apariciones = [];

      $total_s = 0;
      foreach ($a as $key => $value) {
        $empleados_tiempo = DB::table('control_tiempo')
        ->whereBetween('fecha',[$value['i'], $value['f']])
        ->where('proyecto_id',$proyecto->proyecto_id)
        ->select('empleado_asignado_id','fecha')
        ->get();
        $f = [];
        foreach ($empleados_tiempo as $key => $vs) {
          $total_s = 0;

          $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
          ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

          $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::where('nombre',$empleado_->empleado_imss)->get();

          foreach ($SueldoSemanaEmpleado as $key => $value_s) {
            $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::where('fecha_i',$vs->fecha)
            ->whereIn('sueldo_semana_empleado_id',$value_s)
            ->first();

            if (isset($SueldoSemanaEmpleadoRegistros) == true) {
              $total_s += (($SueldoSemanaEmpleadoRegistros->total*7)/6);
            }
          }


          $f [] = $total_s;
        }



        $apariciones []=array_sum($f);
        // $apariciones []= $total_s * count($empleados_tiempo);
      }

      $arreglos_final [] = [
        'proyectos' => $proyecto,
        'apa' => $apariciones,
      ];
    }

    return response()->json($arreglos_final);



    /////////////////////////////


    $valor = strlen('hola');
    return response()->json($valor);
    $valores= explode('&',$id);
    $fechauno = $valores[0];
    $fechados = $valores[1];

    $fechas_siete = [];
    $fechaInicio=strtotime($fechauno);
    $fechaFin=strtotime($fechados);
    // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    //  $fechas [] = date("d-m-Y", $i);
    // }
    for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
      $fechas_siete [] = date("Y-m-d", $i);
    }
    for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
      $fechas_n [] = date("Y-m-d", $i);
    }

    $a = [];
    $tam = count($fechas_siete);


    for ($h=0; $h < $tam ; $h++) {
      $arreglo =[];

      $arreglo_fechas_mostrar = [];
      $arreglo_fechas_buscar = [];
      $fechaInicio=strtotime($fechauno);
      $fechaFin=strtotime($fechados);

      for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
        $arreglo_fechas [] = date("d-M-yy",$i);
        $arreglo_fechas_buscar [] = date('Y-m-d',$i);
      }
      $fechas = array_values(array_unique($arreglo_fechas_buscar));
      $fechas_final = [];
      foreach ($fechas as $key => $value) {
        $fechas_final [] = substr($value,5,2);
      }

      $dia   = substr($fechas_siete[$h],8,2);
      $mes = substr($fechas_siete[$h],5,2);
      $anio = substr($fechas_siete[$h],0,4);
      $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));

      $a [] = [
        'ns' => $semana,
        'i' => $fechas_siete[$h],
        'f' => $fechas_n[$h],
        'd' => $fechas_final,
      ];
    }


    $proyectos_ = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('control_tiempo.supervisor_id',$user->empleado_id)
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('control_tiempo.proyecto_id')
    ->get();
    $arreglo_dos = [];
    $arreglo_proyectos_suma = [];

    foreach ($proyectos_ as $key => $pro) {

      $emplados = DB::table('control_tiempo')
      ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
      ->whereBetween('fecha',[$fechauno, $fechados])
      ->where('proyecto_id',$pro->proyecto_id)
      ->select('empleado_asignado_id',
      DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
      ->groupBy('empleado_asignado_id')
      ->get();

      $arreglo_tres = [];
      $sumatoria_dos = 0;

      foreach ($emplados as $key => $emplado) {
        $arreglo_cuatro = [];

        foreach ($a as $key => $aa) {
          $empleado_dos = DB::table('control_tiempo')
          ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
          ->whereBetween('fecha',[$aa['i'], $aa['f']])
          ->where('proyecto_id',$pro->proyecto_id)
          ->where('empleado_asignado_id',$emplado->empleado_asignado_id)
          ->select('fecha',
          DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
          ->get();


          $sumatoria = 0;
          foreach ($empleado_dos as $key => $empleado_dos_n) {
            $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
            join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
            ->where('sueldo_semana_empleado_registros.fecha_i',$empleado_dos_n->fecha)
            ->where('sse.nombre','LIKE',$empleado_dos_n->nombre.'%')
            ->select("total")
            ->first();
            $total_csct = 0;
            $total_ema_final_csct = 0;
            $total_eba_final_csct = 0;
            $total_ema_final_csct_c = 0;
            $total_eba_final_csct_c = 0;



            $ema_csct = \App\CoutaImssEMA::where('nombre',$empleado_dos_n->nombre)
            ->whereIn('mes',$aa['d'])
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema_csct) > 0) {
              if ($ema_csct[0]->subtotal != 0 ) {
                $total_ema_dia_csct =  $ema_csct[0]->subtotal/$ema_csct[0]->dias;
                $total_ema_final_csct = $total_ema_dia_csct;
              }
            }
            $ema_csct_c = \App\CoutaImssEMACSCT::where('nombre',$empleado_dos_n->nombre)
            ->whereIn('mes',$aa['d'])
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($ema_csct_c) > 0) {
              if ($ema_csct_c[0]->subtotal != 0 ) {
                $total_ema_dia_csct_c =  $ema_csct_c[0]->subtotal/$ema_csct_c[0]->dias;
                $total_ema_final_csct_c = $total_ema_dia_csct_c;
              }
            }
            $eba_csct = \App\CoutaImssEBA::where('nombre',$empleado_dos_n->nombre)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba_csct) > 0) {
              if ($eba_csct[0]->subtotal != 0 ) {
                $total_eba_dia_csct =  $eba_csct[0]->subtotal/$eba_csct[0]->dias;
                $total_eba_final_csct = $total_eba_dia_csct;
              }
            }
            $eba_csct_c = \App\CoutaImssEBACSCT::where('nombre',$empleado_dos_n->nombre)
            // ->whereIn('mes',$fechas_final)
            ->where('anio',date('Y'))
            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
            ->get();
            if (count($eba_csct_c) > 0) {
              if ($eba_csct_c[0]->subtotal != 0 ) {
                $total_eba_dia_csct_c =  $eba_csct_c[0]->subtotal/$eba_csct_c[0]->dias;
                $total_eba_final_csct_c = $total_eba_dia_csct_c;
              }
            }



            $sumatoria += isset($SueldoSemanaEmpleadoRegistros) == true ? $SueldoSemanaEmpleadoRegistros->total + $total_ema_final_csct + $total_ema_final_csct_c + $total_eba_final_csct + $total_eba_final_csct_c  : 0;
          }


          $arreglo_cuatro [] = $sumatoria;
        }
        $arreglo_tres [] = ['nombre' => $emplado->nombre,'datos' => $arreglo_cuatro];
        $sumatoria_dos += array_sum($arreglo_cuatro);

      }
      // $arreglo_tres = [];
      // foreach ($a as $key => $as) {
      //     $emplados = DB::table('control_tiempo')
      //     ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
      //     ->whereBetween('fecha',[$as['i'], $as['f']])
      //     ->where('proyecto_id',$pro->proyecto_id)
      //     ->select('empleado_asignado_id',
      //     DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
      //     ->groupBy('empleado_asignado_id')
      //     ->get();
      //     // $arreglo_cuatro = [];
      //     // foreach ($emplados as $key => $e) {
      //     //   $arreglo_cuatro [] = $e;
      //     // }
      //     $arreglo_tres [] = ['uno' => $emplados];
      // }
      $arreglo_dos [] = ['proyecto' => $pro,'empleados' => $arreglo_tres];
      $arreglo_proyectos_suma  [] = ['proyecto' => $pro, 'total' => $sumatoria_dos];


    }




    return response()->json($arreglo_proyectos_suma);
  }

  public function facturasentradasfixed_dos_s($id ='2020-06-29&2020-07-05&27')
  {
    // code...
    $valores= explode('&',$id);
    $fechauno = $valores[0];
    $fechados = $valores[1];
    $semana = $valores[2];
    // $user = Auth::user();



    $proyectos_ = DB::table('control_tiempo')
    ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
    ->whereBetween('fecha',[$fechauno, $fechados])
    // ->where('control_tiempo.supervisor_id',$user->empleado_id)
    ->select('control_tiempo.proyecto_id','p.nombre_corto')
    ->groupBy('control_tiempo.proyecto_id')
    ->get();

    $arreglo_proyectos = [];
    foreach ($proyectos_ as $key => $proyecto) {
      $arreglo =[];


      $emplados = DB::table('control_tiempo')
      ->join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
      ->whereBetween('fecha',[$fechauno, $fechados])

      // ->where('control_tiempo.supervisor_id',$user->empleado_id)
      ->where('proyecto_id',$proyecto->proyecto_id)
      ->select('empleado_asignado_id',
      DB::raw("CONCAT(e.ap_paterno,' ',e.ap_materno,' ',e.nombre) AS nombre"))
      ->groupBy('empleado_asignado_id')
      ->get();

      foreach ($emplados as $key => $empleado) {

        $arreglo_dos = [];
        $totales_periodo = [];
        $emplados_dos = DB::table('control_tiempo')
        ->whereBetween('fecha',[$fechauno, $fechados])
        // ->where('control_tiempo.supervisor_id',$user->empleado_id)
        ->where('proyecto_id',$proyecto->proyecto_id)
        ->where('empleado_asignado_id',$empleado->empleado_asignado_id)
        ->select('empleado_asignado_id','fecha')
        ->orderBy('fecha')
        ->get();
        foreach ($emplados_dos as $key => $a) {


          $empleado_ = \App\Empleado::where('id',$a->empleado_asignado_id)
          ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();

          $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
          join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
          ->where('sueldo_semana_empleado_registros.fecha_i',$a->fecha)
          ->where('sse.nombre','LIKE',$empleado_->empleado_imss.'%')
          ->select("total")
          ->first();

          $total = 0;
          if (isset($SueldoSemanaEmpleadoRegistros) == true) {
            $total = $SueldoSemanaEmpleadoRegistros->total;
          }

          // $arreglo_fechas_buscar = [];
          // $fechaInicio=strtotime($fechauno);
          // $fechaFin=strtotime($fechados);
          //
          // for($f=$fechaInicio; $f<=$fechaFin; $f+=86400){
          //   // $arreglo_fechas_buscar [] = date('Y-m-d',$f);
          //   // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
          //   if ($a->fecha === date('Y-m-d',$f)) {
          //     // code...
          //   }
          // }

          $arreglo_dos [] =
          ['fecha' => $a->fecha,
          'total' => $total,

        ];


        $totales_periodo [] = $total;

        $date_1a = new DateTime($fechauno);
        $date_2a = new DateTime($arreglo_dos[0]['fecha']);
        $diff = $date_1a->diff($date_2a);


        $f_f = (7 - count($arreglo_dos)) - $diff->days;

      }


      $arreglo [] =
      ['empleados' => $empleado->nombre,
      'registros' => $arreglo_dos,
      'd' => $diff->days,
      'f' => $f_f,
      'total' => array_sum($totales_periodo),]
      ;
      // 'empleado' => $arreglo_dos,
      // 'total' => $total

    }




    $arreglo_proyectos [] =
    [
      'proyecto' => $proyecto->nombre_corto,
      'total' => $arreglo,
    ];
  }
  return response()->json($arreglo_proyectos);
}

public function facturasentradasfixe_($id ='2020-06-29&2020-07-26&27')
{
  // $valores= explode('&',$id);
  // $fechauno = $valores[0];
  // $fechados = $valores[1];



  $valores= explode('&',$id);
  $fechauno = $valores[0];
  $fechados = $valores[1];
  $semana = $valores[2];
  $user = Auth::user();




  $proyectos_ = DB::table('control_tiempo')
  ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
  ->whereBetween('fecha',[$fechauno, $fechados])
  // ->where('control_tiempo.supervisor_id',$user->empleado_id)
  ->select('control_tiempo.proyecto_id','p.nombre_corto')
  ->groupBy('control_tiempo.proyecto_id')
  ->get();

  $arreglo_proyectos = [];
  foreach ($proyectos_ as $key => $proyecto) {
    // $arreglo =[];
    //
    // $emplados = DB::table('control_tiempo')
    // ->whereBetween('fecha',[$fechauno, $fechados])
    // // ->where('control_tiempo.supervisor_id',$user->empleado_id)
    // ->where('proyecto_id',$proyecto->proyecto_id)
    // ->select('empleado_asignado_id','fecha')
    // ->get();



    //
    //
    // $arreglo_fechas_mostrar = [];
    // $arreglo_fechas_buscar = [];
    // $arreglo_dias = [];
    //
    // $fechaInicio=strtotime($fechauno);
    // $fechaFin=strtotime($fechados);
    //
    // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
    //   $arreglo_fechas [] = date("d-M-yy",$i);
    //   $arreglo_fechas_buscar [] = date('Y-m-d',$i);
    //   $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
    // }

    // foreach($daterange as $date){
    //   $arreglo_fechas [] =$date->format("d-M-yy");
    //   $arreglo_fechas_buscar [] = $date->format('Y-m-d');
    //   $arreglo_dias [] = $this->get_nombre_dia($date->format('Y-m-d'));
    // }
    //
    // foreach ($emplados as $key => $empleado) {
    //   $arreglo_f = [];
    //   $emplado_nombre = DB::table('empleados AS e')->
    //   select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
    //   ->where('id',$empleado->empleado_asignado_id)
    //   ->first();
    //   for ($i=0; $i < count($arreglo_fechas_buscar); $i++) {
    //     $controltiempo = DB::table('control_tiempo')
    //     ->join('empleados as E', 'E.id', '=', 'control_tiempo.empleado_asignado_id')
    //     ->join('empleados as EE','EE.id','=','control_tiempo.supervisor_id')
    //     ->leftJoin('proyectos', 'proyectos.id', '=', 'control_tiempo.proyecto_id')
    //     ->select('control_tiempo.*',
    //     'proyectos.nombre_corto',
    //     DB::raw("CONCAT(E.nombre,' ',E.ap_paterno,' ',E.ap_materno) AS asignado_nombre"),
    //     DB::raw("CONCAT(EE.nombre,' ',EE.ap_paterno,' ',EE.ap_materno) AS supervisor_nombre"),
    //     DB::raw("CONCAT(E.ap_paterno,' ',E.ap_materno,' ',E.nombre) AS nombre_a"))
    //     ->where('control_tiempo.fecha',$arreglo_fechas_buscar[$i])
    //     // ->where('supervisor_id',$user->empleado_id)
    //     ->where('control_tiempo.empleado_asignado_id',$empleado->empleado_asignado_id)
    //     ->first();
    //     $empleado_ = \App\Empleado::where('id',$empleado->empleado_asignado_id)
    //     ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();
    //
    //     $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
    //     join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
    //     ->where('sueldo_semana_empleado_registros.fecha_i',$arreglo_fechas_buscar[$i])
    //     ->where('sse.nombre','LIKE',$empleado_->empleado_imss.'%')
    //     ->select("total")
    //     ->first();
    //     $total = 0;
    //
    //     if (isset($SueldoSemanaEmpleadoRegistros) == true) {
    //       $total = $SueldoSemanaEmpleadoRegistros->total;
    //     }
    //
    //     $arreglo_f [] = [(isset($controltiempo) == true) ? $controltiempo->nombre_corto.'s'.$total :'-'.$total];
    //   }
    //   $arreglo [] =[
    //     'empleado' => $emplado_nombre->empleado_nombre,
    //     'data' => $arreglo_f,
    //
    //   ];
    // }


    $arreglo_proyectos [] =
    [
      'proyecto' => $proyecto,
      // 'total' => $arreglo,
    ];
  }
  return response()->json($arreglo_proyectos);
  //
  // $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
  // join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
  // ->whereBetween('fecha_i',[$fechauno, $fechados])
  // // ->whereIn('sueldo_semana_empleado_id',$array_sin_repetidos)
  // ->select('sse.nombre','sse.id')
  // // ->select(DB::raw("SUM(total) AS total"))
  // ->groupBy('sse.id')
  // ->get();
  //
  // $Controltiempo = Controltiempo::
  // join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
  // ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS nombre"))
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // ->groupBy('empleado_asignado_id')
  // ->get();
  //
  //
  // $array_sin_repetidos = [];
  // $array_sin_repetidos_nombre = [];
  // foreach ($SueldoSemanaEmpleadoRegistros as $key => $sse) {
  //   $nombre = 0;
  //   foreach ($Controltiempo as $key => $ct) {
  //     if (trim($sse->nombre) === $ct->nombre) {
  //       $nombre ++;
  //     }
  //   }
  //
  //   if ($nombre > 0) {
  //     $array_sin_repetidos [] = $sse->id;
  //     $array_sin_repetidos_nombre [] = $sse;
  //   }
  // }
  //
  // // return response()->json(count($array_sin_repetidos_nombre));
  // return response()->json($array_sin_repetidos_nombre);

  //
  // $Controltiempo = Controltiempo::
  // join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
  // ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS nombre"))
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // ->groupBy('empleado_asignado_id')
  // ->get();
  // $total = 0;
  // foreach ($Controltiempo as $key => $Controltiempo_) {
  //   $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
  //   join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
  //   ->whereBetween('sueldo_semana_empleado_registros.fecha_i',[$fechauno, $fechados])
  //   ->where('sse.nombre',$Controltiempo_->nombre)
  //   // ->whereIn('sueldo_semana_empleado_id',$array_sin_repetidos)
  //   // ->select('sse.nombre')
  //   ->select(DB::raw("SUM(total) AS total"))
  //   // ->groupBy('sse.id')
  //   ->first();
  //   if (isset($SueldoSemanaEmpleadoRegistros) == true) {
  //     $total += $SueldoSemanaEmpleadoRegistros->total;
  //   }
  //
  // }
  // $arra_ids = $this->arraySinDuplicados($id);
  //
  // $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
  // join('sueldo_semana_empleado AS sse','sse.id','=','sueldo_semana_empleado_registros.sueldo_semana_empleado_id')
  // ->whereBetween('fecha_i',[$fechauno, $fechados])
  // ->whereIn('sueldo_semana_empleado_id',$arra_ids)
  // // ->select('sse.nombre')
  // ->select(DB::raw("SUM(total) AS total"))
  // // ->groupBy('sse.id')
  // ->first();
  // // return response()->json($SueldoSemanaEmpleadoRegistros->total);
  //
  // $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::select('id','nombre')->get();
  //
  // // return response()->json(count($SueldoSemanaEmpleadoRegistros));
  //
  // $valores= explode('&',$id);
  // $fechauno = $valores[0];
  // $fechados = $valores[1];
  //
  // $fechas = [];
  // $fechas_siete = [];
  // $fechaInicio=strtotime($fechauno);
  // $fechaFin=strtotime($fechados);
  // // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  // //  $fechas [] = date("d-m-Y", $i);
  // // }
  // for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
  //   $fechas_siete [] = date("Y-m-d", $i);
  // }
  // for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
  //   $fechas_n [] = date("Y-m-d", $i);
  // }
  //
  // $a = [];
  //
  // $tam = count($fechas_siete);
  //
  //
  // for ($h=0; $h < $tam ; $h++) {
  //   $arreglo =[];
  //
  //   $arreglo_fechas_mostrar = [];
  //   $arreglo_fechas_buscar = [];
  //   $fechaInicio=strtotime($fechauno);
  //   $fechaFin=strtotime($fechados);
  //
  //   for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //     $arreglo_fechas [] = date("d-M-yy",$i);
  //     $arreglo_fechas_buscar [] = date('Y-m-d',$i);
  //     // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
  //   }
  //
  //   $dia   = substr($fechas_siete[$h],8,2);
  //   $mes = substr($fechas_siete[$h],5,2);
  //   $anio = substr($fechas_siete[$h],0,4);
  //   $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));
  //
  //   $a [] = [
  //     'ns' => $semana,
  //     'i' => $fechas_siete[$h],
  //     'f' => $fechas_n[$h],
  //   ];
  //
  //
  // //
  // }
  //
  //
  //   $Controltiempo = Controltiempo::
  //   join('empleados AS e','e.id','=','control_tiempo.empleado_asignado_id')
  //   ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS nombre"))->groupBy('empleado_asignado_id')->get();
  //
  //   $SueldoSemanaEmpleado = \App\SueldoSemanaEmpleado::select('id','nombre')->get();
  //   $array_sin_repetidos = [];
  //   $array_sin_repetidos_nombre = [];
  //   foreach ($SueldoSemanaEmpleado as $key => $sse) {
  //     $nombre = 0;
  //     foreach ($Controltiempo as $key => $ct) {
  //       if (trim($sse->nombre) === trim($ct->nombre)) {
  //         $nombre ++;
  //       }
  //     }
  //
  //     if ($nombre == 0) {
  //       $array_sin_repetidos [] = $sse->id;
  //       $array_sin_repetidos_nombre [] = $sse->nombre;
  //     }
  //   }
  //
  //   $apariciones = [];
  //
  //   $total_conserflow = 0;
  //   $total_ema_final = 0;
  //   $total_eba_final = 0;
  //   $total_ema_final_c_csct = 0;
  //   $total_eba_final_c_csct = 0;
  //   foreach ($a as $key => $value) {
  //
  //     $SueldoSemanaEmpleadoRegistros = \App\SueldoSemanaEmpleadoRegistros::
  //     whereBetween('fecha_i',[$value['i'], $value['f']])
  //     ->whereIn('sueldo_semana_empleado_id',$array_sin_repetidos)
  //     ->select(DB::raw("SUM(total) AS total"))
  //     ->first();
  //
  //     $arreglo_fechas_buscar_a = [];
  //     $fechaInicio_a=strtotime($value['i']);
  //     $fechaFin_a=strtotime($value['f']);
  //     for($m=$fechaInicio_a; $m<=($fechaFin_a+86400); $m+=86400){
  //     $arreglo_fechas_buscar_a [] = date('Y-m',$m);
  //     }
  //     $fechas_a = array_values(array_unique($arreglo_fechas_buscar_a));
  //     $fechas_final_a = [];
  //     foreach ($fechas_a as $key => $value_a) {
  //       $fechas_final_a [] = substr($value_a,5,2);
  //     }
  //
  //     $ema = \App\CoutaImssEMA::
  //     whereIn('nombre',$array_sin_repetidos_nombre)
  //     ->whereIn('mes',$fechas_final_a)
  //     ->where('anio',date('Y'))
  //     ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //     ->get();
  //     if (count($ema) > 0) {
  //       if ($ema[0]->subtotal != 0 ) {
  //         $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
  //         $total_ema_final += $total_ema_dia;
  //       }
  //     }
  //     $ema_c_csct = \App\CoutaImssEMACSCT::
  //     whereIn('nombre',$array_sin_repetidos_nombre)
  //     ->whereIn('mes',$fechas_final_a)
  //     ->where('anio',date('Y'))
  //     ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //     ->get();
  //     if (count($ema_c_csct) > 0) {
  //       if ($ema_c_csct[0]->subtotal != 0 ) {
  //         $total_ema_dia_c_csct =  $ema_c_csct[0]->subtotal/$ema_c_csct[0]->dias;
  //         $total_ema_final_c_csct += $total_ema_dia_c_csct;
  //       }
  //     }
  //     $eba = \App\CoutaImssEBA::
  //     whereIn('nombre',$array_sin_repetidos_nombre)
  //     // ->whereIn('mes',$fechas_final)
  //     ->where('anio',date('Y'))
  //     ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //     ->get();
  //     if (count($eba) > 0) {
  //       if ($eba[0]->subtotal != 0 ) {
  //         $total_eba_dia =  $eba[0]->subtotal/$eba[0]->dias;
  //         $total_eba_final += $total_eba_dia;
  //       }
  //     }
  //     $eba_c_csct = \App\CoutaImssEBACSCT::
  //     whereIn('nombre',$array_sin_repetidos_nombre)
  //     // ->whereIn('mes',$fechas_final)
  //     ->where('anio',date('Y'))
  //     ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //     ->get();
  //     if (count($eba_c_csct) > 0) {
  //       if ($eba_c_csct[0]->subtotal != 0 ) {
  //         $total_eba_dia_c_csct =  $eba_c_csct[0]->subtotal/$eba_c_csct[0]->dias;
  //         $total_eba_final_c_csct += $total_eba_dia_c_csct;
  //       }
  //     }
  //
  //
  //
  //     $apariciones [] =
  //       [ $SueldoSemanaEmpleadoRegistros->total + $total_ema_final + $total_ema_final_c_csct + $total_eba_final + $total_eba_final_c_csct]
  //     ;
  //   }
  //
  //
  //
  //   return response()->json($apariciones);
  //     // $week = 1;
  //     // $a = $this->getInicioFinSemana(31,2020);
  //     //
  //     // $arreglo_fechas_buscar = [];
  //     // $fechaInicio=strtotime($a['inicio']);
  //     // $fechaFin=strtotime($a['fin']);
  //     //
  //     // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //     //   $arreglo_fechas_buscar [] = date('Y-m-d',$i);
  //     // }
  //     // return response()->json($arreglo_fechas_buscar);
  //
  //     $valores= explode(':',$id);
  //     if ($valores > 1) {
  //     $cadena_hora = preg_replace('([^A-Za-z0-9 ])','',$id);
  //     }
  //     return response()->json($cadena_hora);
  //
  //     $fechauno = $valores[0];
  //     $fechados = $valores[1];
  //
  //     $arreglo_fechas_buscar = [];
  //     $fechaInicio=strtotime($valores[0]);
  //     $fechaFin=strtotime($valores[1]);
  //
  //     for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //       $arreglo_fechas_buscar [] = date('Y-m-d',$i);
  //     }
  //     return response()->json($arreglo_fechas_buscar);
  //
  //     $fechas = [];
  //     $fechas_siete = [];
  //     $fechaInicio=strtotime($fechauno);
  //     $fechaFin=strtotime($fechados);
  //     // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //     //  $fechas [] = date("d-m-Y", $i);
  //     // }
  //     for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
  //       $fechas_siete [] = date("Y-m-d", $i);
  //     }
  //     for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
  //       $fechas_n [] = date("Y-m-d", $i);
  //     }
  //     // return response()->json(count($fechas_n));
  //
  //     $a = [];
  //     $user = Auth::user();
  //     $tam = count($fechas_siete);
  //     $proyectos = DB::table('control_tiempo')
  //     ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
  //     ->whereBetween('fecha',[$fechauno, $fechados])
  //     // ->where('control_tiempo.supervisor_id',$user->empleado_id)
  //     ->select('control_tiempo.proyecto_id','p.nombre_corto')
  //     ->groupBy('control_tiempo.proyecto_id')
  //     ->get();
  //
  //     $arreglo_proyectos = [];
  //
  //     foreach ($proyectos as $key => $proyecto) {
  //       $empleados_p = DB::table('control_tiempo')
  //       ->whereBetween('fecha',[$fechauno, $fechados])
  //       // ->where('control_tiempo.supervisor_id',$user->empleado_id)
  //       ->where('proyecto_id',$proyecto->proyecto_id)->get();
  //
  //       $total = 0;
  //       $total_ema_final_p = 0;
  //       foreach ($empleados_p as $key => $empleado) {
  //         $empleado_ = \App\Empleado::where('id',$empleado->empleado_asignado_id)
  //         ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();
  //
  //         $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
  //         if (count($contratos) > 0) {
  //           $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
  //           if (count($sueldos) > 0) {
  //             $total += $sueldos[0]->sueldo_diario_real;
  //           }
  //           $total += 0;
  //         }else {
  //           $total += 0;
  //         }
  //
  //         $mes = substr($empleado->fecha,5,2);
  //
  //         $ema_p = \App\CoutaImssEMA::where('nombre',$empleado_->empleado_imss)
  //         ->where('mes',$mes)
  //         ->where('anio',date('Y'))
  //         ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //         ->get();
  //         if (count($ema_p) > 0) {
  //           if ($ema_p[0]->subtotal != 0 ) {
  //             $total_ema_dia_p =  $ema_p[0]->subtotal/$ema_p[0]->dias;
  //             $total_ema_final_p+= $total_ema_dia_p ;
  //           }
  //         }
  //       }
  //       $arreglo_proyectos [] =
  //       [
  //         'proyecto' => $proyecto,
  //         'total' => $total + $total_ema_final_p,
  //       ];
  //     }
  //
  //     $emplados = DB::table('control_tiempo')
  //     ->whereBetween('fecha',[$fechauno, $fechados])
  //     // ->where('supervisor_id',$user->empleado_id)
  //     ->select('empleado_asignado_id')
  //     ->groupBy('empleado_asignado_id')
  //     ->get();
  //
  //     for ($h=0; $h < $tam ; $h++) {
  //       $arreglo =[];
  //
  //
  //       $emplado_supervisor = DB::table('empleados AS e')->
  //       select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
  //       ->where('id',$user->empleado_id)
  //       ->first();
  //
  //       $arreglo_fechas_mostrar = [];
  //       $arreglo_fechas_buscar = [];
  //       $fechaInicio=strtotime($fechauno);
  //       $fechaFin=strtotime($fechados);
  //
  //       for($i=$fechaInicio; $i<=($fechaFin+86400); $i+=86400){
  //         $arreglo_fechas [] = date("d-M-yy",$i);
  //         $arreglo_fechas_buscar [] = date('Y-m',$i);
  //         // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
  //       }
  //
  //       $dia   = substr($fechas_siete[$h],8,2);
  //       $mes = substr($fechas_siete[$h],5,2);
  //       $anio = substr($fechas_siete[$h],0,4);
  //       $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));
  //
  //       $a [] = [
  //         'ns' => $semana,
  //         'i' => $fechas_siete[$h],
  //         'f' => $fechas_n[$h],
  //       ];
  //
  //
  //     //
  //     }
  //     $fechas = array_values(array_unique($arreglo_fechas_buscar));
  //     $fechas_final = [];
  //     foreach ($fechas as $key => $value) {
  //       $fechas_final [] = substr($value,5,2);
  //     }
  //     return response()->json($fechas_final);
  //     $proyectos = DB::table('control_tiempo')
  //     ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
  //     ->whereBetween('fecha',[$fechauno, $fechados])
  //     ->select('control_tiempo.proyecto_id','p.nombre_corto')
  //     ->groupBy('proyecto_id')
  //     ->get();
  //     $arreglos_final = [];
  //
  //     foreach ($proyectos as $key => $proyecto) {
  //       $apariciones = [];
  //       $total_s = 0;
  //
  //       foreach ($a as $key => $value) {
  //
  //         $empleados_tiempo = DB::table('control_tiempo')
  //         ->whereBetween('fecha',[$value['i'], $value['f']])
  //         ->where('proyecto_id',$proyecto->proyecto_id)
  //         ->select('empleado_asignado_id','fecha')
  //         ->get();
  //
  //         $f = [];
  //         $f_i = [];
  //         foreach ($empleados_tiempo as $key => $vs) {
  //           $total_s = 0;
  //           $total_ema_final = 0;
  //           $contratos = DB::table('control_tiempo')
  //            ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
  //            ->select('c.*')
  //            // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
  //            ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
  //            ->orderBy('id', 'desc')->get();
  //
  //            if (count($contratos) > 0) {
  //              $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
  //              if (count($sueldos) > 0) {
  //                $total_s += $sueldos[0]->sueldo_diario_real;
  //              }else {
  //                $total_s += 0;
  //              }
  //            }else {
  //              $total_s += 0;
  //            }
  //
  //            $mes = substr($vs->fecha,5,2);
  //            $empleado_ = \App\Empleado::where('id',$vs->empleado_asignado_id)
  //            ->select(DB::raw("CONCAT(ap_paterno,' ',ap_materno,' ',nombre) AS empleado_imss"))->first();
  //
  //            $ema = \App\CoutaImssEMA::where('nombre',$empleado_->empleado_imss)
  //            ->where('mes',$mes)
  //            ->where('anio',date('Y'))
  //            ->select(DB::raw('SUM(dias) as dias'),DB::raw('SUM(subtotal) as subtotal'))
  //            ->get();
  //            if (count($ema) > 0) {
  //              if ($ema[0]->subtotal != 0 ) {
  //                $total_ema_dia =  $ema[0]->subtotal/$ema[0]->dias;
  //                $total_ema_final = $total_ema_dia;
  //              }
  //            }
  //            $f_i [] = $total_ema_final;
  //           $f [] = $total_s;
  //         }
  //
  //
  //
  //         // $apariciones []=array_sum($f);
  //         $apariciones []=$f;
  //         $apariciones_uno []=$f_i;
  //         // $apariciones []= $total_s * count($empleados_tiempo);
  //       }
  //
  //       $arreglos_final [] = [
  //         'proyectos' => $proyecto,
  //         'apa' => $apariciones,
  //         'apaj' => $apariciones_uno,
  //       ];
  //     }
  //
  //     return response()->json($arreglos_final);

  ////////////////////////////////////////////////////
  //
  // $contratos_conserflow = Contrato::join('empleados AS e','e.id','=','contratos.empleado_id')
  // ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_empleado"),
  // 'contratos.id','fecha_ingreso','fecha_fin','empleado_id'
  // )->where('tipo_nomina_id','2')->where('contratos.condicion','1')->orderBy('empleado_id')->get();
  // $contratos_sueldos = [];
  // $total_conserflow = 0;
  // foreach ($contratos_conserflow as $key => $value) {
  //   $sueldos_conserflow = Sueldo::where('contrato_id',$value->id)->orderBy('id', 'desc')->get();
  //   $contratos_sueldos [] =[$value,$sueldos_conserflow];
  //     if (count($sueldos_conserflow) > 0) {
  //       $total_conserflow += $sueldos_conserflow[0]->sueldo_diario_real;
  //     }else {
  //       $total_conserflow += 0;
  //     }
  // }
  //
  // $contratos_csct = ContratoDBP::join('empleados AS e','e.id','=','contratos.empleado_id')
  // ->select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_empleado"),
  // 'contratos.id','fecha_ingreso','fecha_fin','empleado_id'
  // )->where('tipo_nomina_id','2')->where('contratos.condicion','1')->orderBy('empleado_id')->get();
  // $contratos_sueldos_csct = [];
  // $total_csct = 0;
  // foreach ($contratos_csct as $key => $value) {
  //   $sueldos_csct = SueldoDBP::where('contrato_id',$value->id)->orderBy('id', 'desc')->get();
  //   $contratos_sueldos_csct [] =[$value,$sueldos_csct];
  //     if (count($sueldos_csct) > 0) {
  //       $total_csct += $sueldos_csct[0]->sueldo_diario_real;
  //     }else {
  //       $total_csct += 0;
  //     }
  // }
  // $total_sueldos = $total_conserflow + $total_csct;
  //
  // return response()->json($contratos_sueldos);


  //
  // $total_s = 0;
  //
  // $contratos = DB::table('control_tiempo')
  //  ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
  //  ->select('c.*')
  //  // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
  //  ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
  //  ->orderBy('id', 'desc')->get();
  //  if (count($contratos) > 0) {
  //    $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
  //    if (count($sueldos) > 0) {
  //      $total_s += $sueldos[0]->sueldo_diario_real;
  //    }else {
  //      $total_s += 0;
  //    }
  //  }else {
  //    $total_s += 0;
  //  }
  //
  // $f [] = $total_s;
  //

  /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  // $arreglo = [];
  // $compras = Compras::where('conrequisicion','0')->where('proyecto_id',$id)->get();
  // foreach ($compras as $key => $compra) {
  //
  //   $requisicionhasordencompras = requisicionhasordencompras::where('orden_compra_id',$compra->id)
  //   ->select('requisicione_id','orden_compra_id')->get();
  //   $arreglo [] = [
  //     'compra' => $compra->folio,
  //     'partidas' => $requisicionhasordencompras[0],
  //     'key' => $key,
  //   ];
  // }
  // $arreglo_requisicion= [];
  // foreach ($arreglo as $key => $value) {
  //   // code...
  //   $requisicion = Requisicion::orderBy('id', 'asc')
  //   ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
  //   ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
  //   ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
  //   ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
  //   ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
  //   ->select('requisiciones.*','p.nombre AS nombrep','p.nombre_corto as p_nombre_corto',
  //   DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_empleado_solicita"),
  //   DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"),
  //   DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombre_empleado_valida"),
  //   DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombre_empleado_recibe"))
  //   ->where('requisiciones.id','=',$value['partidas']->requisicione_id)
  //   // ->where('requisiciones.estado_id','!=','9')->where('requisiciones.estado_id','!=','11')
  //   ->first();
  //   $arreglo_requisicion [] =
  //     $requisicion
  //   ;
  // }
  // return response()->json($arreglo);






  // $arreglo = [];
  // $compras = Compras::where('proyecto_id','90')->get();
  // $total_usd_oc = 0; $total_mnx_oc = 0;
  // foreach ($compras as $key => $compra) {
  //   if ($compra->moneda == 1) {
  //     $total_usd_oc += (floatval(str_replace(',','',$compra->total)));
  //   }elseif ($compra->moneda == 2) {
  //     $total_mnx_oc += (floatval(str_replace(',','',$compra->total)));
  //   }
  //
  // }
  // $facturas = FacturasEntradas::Join('ordenes_compras AS oc','oc.id','=','facturas_entradas.orden_compra_id')
  // ->where('oc.proyecto_id','90')->where('facturas_entradas.total_factura','!=','')->get();
  // $total_usd_f = 0;$total_mnx_f = 0;
  // foreach ($facturas as $key => $factura) {
  //   if ($factura->moneda == 1) {
  //     $total_usd_f += (floatval(str_replace(',','',$factura->total_factura)));
  //   }elseif ($factura->moneda == 2) {
  //     $total_mnx_f += (floatval(str_replace(',','',$factura->total_factura)));
  //   }
  // }
  // $pagos = PagoCompra::join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
  // ->join('ordenes_compras AS oc','oc.id','=','pnr.ordenes_comp_id')
  // ->select('pagos_compras.*')
  // ->where('oc.proyecto_id','90')->get();
  // $total_usd_p = 0;$total_mnx_p = 0;
  // foreach ($pagos as $key => $pago) {
  //   if ($pago->tipo_cambio == 1) {
  //     $total_usd_p += (floatval(str_replace(',','',$pago->cargo)));
  //   }elseif ($pago->tipo_cambio == 2) {
  //     $total_mnx_p += (floatval(str_replace(',','',$pago->cargo)));
  //   }
  // }
  //
  // $arreglo [] = [
  //   'total_mnx_oc' => $total_mnx_oc,
  //   'total_usd_oc' => $total_usd_oc,
  //   'total_mnx_f' => $total_mnx_f,
  //   'total_usd_f' => $total_usd_f,
  //   'total_mnx_p' => $total_mnx_p,
  //   'total_usd_p' => $total_usd_p,
  // ];
  // return response()->json($arreglo);



  // $valores= explode('&',$this->id);
  // $valores= explode('&',$data);
  // $fechauno = $valores[0];
  // $fechados = $valores[1];
  //
  // $fechas = [];
  // $fechas_siete = [];
  // $fechaInicio=strtotime($fechauno);
  // $fechaFin=strtotime($fechados);
  // // for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  // //  $fechas [] = date("d-m-Y", $i);
  // // }
  // for($i=$fechaInicio; $i<=$fechaFin; $i+=604800){
  //   $fechas_siete [] = date("Y-m-d", $i);
  // }
  // for($i=($fechaInicio +518400); $i<=($fechaFin+604800); $i+=604800){
  //   $fechas_n [] = date("Y-m-d", $i);
  // }
  // // return response()->json(count($fechas_n));
  //
  // $a = [];
  // $user = Auth::user();
  // $tam = count($fechas_siete);
  // $proyectos = DB::table('control_tiempo')
  // ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // // ->where('control_tiempo.supervisor_id',$user->empleado_id)
  // ->select('control_tiempo.proyecto_id','p.nombre_corto')
  // ->groupBy('control_tiempo.proyecto_id')
  // ->get();
  //
  // $arreglo_proyectos = [];
  // foreach ($proyectos as $key => $proyecto) {
  //   $empleados_p = DB::table('control_tiempo')
  //   ->whereBetween('fecha',[$fechauno, $fechados])
  //   // ->where('control_tiempo.supervisor_id',$user->empleado_id)
  //   ->where('proyecto_id',$proyecto->proyecto_id)->get();
  //
  //   $total = 0;
  //
  //   foreach ($empleados_p as $key => $empleado) {
  //     $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
  //     if (count($contratos) > 0) {
  //       $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]['id'])->orderBy('id', 'desc')->get();
  //       if (count($sueldos) > 0) {
  //         $total += $sueldos[0]->sueldo_diario_real;
  //       }
  //       $total += 0;
  //     }else {
  //       $total += 0;
  //     }
  //   }
  //   $arreglo_proyectos [] =
  //   [
  //     'proyecto' => $proyecto,
  //     'total' => $total,
  //   ];
  // }
  // $emplados = DB::table('control_tiempo')
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // // ->where('supervisor_id',$user->empleado_id)
  // ->select('empleado_asignado_id')
  // ->groupBy('empleado_asignado_id')
  // ->get();
  //
  // for ($h=0; $h < $tam ; $h++) {
  //   $arreglo =[];
  //
  //
  //   $emplado_supervisor = DB::table('empleados AS e')->
  //   select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
  //   ->where('id',$user->empleado_id)
  //   ->first();
  //
  //   $arreglo_fechas_mostrar = [];
  //   $arreglo_fechas_buscar = [];
  //   $fechaInicio=strtotime($fechauno);
  //   $fechaFin=strtotime($fechados);
  //
  //   for($i=$fechaInicio; $i<=$fechaFin; $i+=86400){
  //     $arreglo_fechas [] = date("d-M-yy",$i);
  //     $arreglo_fechas_buscar [] = date('Y-m-d',$i);
  //     // $arreglo_dias [] = $this->get_nombre_dia(date('Y-m-d',$i));
  //   }
  //
  //   $dia   = substr($fechas_siete[$h],8,2);
  //   $mes = substr($fechas_siete[$h],5,2);
  //   $anio = substr($fechas_siete[$h],0,4);
  //   $semana = date('W',  mktime(0,0,0,$mes,$dia,$anio));
  //
  //   $a [] = [
  //     'ns' => $semana,
  //     'i' => $fechas_siete[$h],
  //     'f' => $fechas_n[$h],
  //   ];
  //
  //
  // //
  // }
  //
  // $proyectos = DB::table('control_tiempo')
  // ->leftJoin('proyectos AS p','p.id','=','control_tiempo.proyecto_id')
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // ->select('control_tiempo.proyecto_id','p.nombre_corto')
  // ->groupBy('proyecto_id')
  // ->get();
  // $arreglos_final = [];
  // foreach ($proyectos as $key => $proyecto) {
  //   $apariciones = [];
  //
  //   $total_s = 0;
  //   foreach ($a as $key => $value) {
  //     $empleados_tiempo = DB::table('control_tiempo')
  //     ->whereBetween('fecha',[$value['i'], $value['f']])
  //     ->where('proyecto_id',$proyecto->proyecto_id)
  //     ->select('empleado_asignado_id')
  //     ->get();
  //     $f = [];
  //     foreach ($empleados_tiempo as $key => $vs) {
  //       $total_s = 0;
  //
  //       $contratos = DB::table('control_tiempo')
  //        ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
  //        ->select('c.*')
  //        // ->whereBetween('control_tiempo.fecha',[$value['i'], $value['f']])
  //        ->where('control_tiempo.empleado_asignado_id',$vs->empleado_asignado_id)
  //        ->orderBy('id', 'desc')->get();
  //        if (count($contratos) > 0) {
  //          $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
  //          if (count($sueldos) > 0) {
  //            $total_s += $sueldos[0]->sueldo_diario_real;
  //          }else {
  //            $total_s += 0;
  //          }
  //        }else {
  //          $total_s += 0;
  //        }
  //
  //       $f [] = $total_s;
  //     }
  //
  //
  //
  //     $apariciones []=array_sum($f);
  //     // $apariciones []= $total_s * count($empleados_tiempo);
  //   }
  //
  //   $arreglos_final [] = [
  //     'proyectos' => $proyecto,
  //     'apa' => $apariciones,
  //   ];
  // }
  //
  //
  //   return response()->json($arreglos_final);




  //
  // $emplados = DB::table('control_tiempo')
  // ->whereBetween('fecha',[$fechauno, $fechados])
  // ->select('empleado_asignado_id')
  // ->groupBy('empleado_asignado_id')
  // ->get();
  //
  // foreach ($emplados as $key => $empleado) {
  //
  //   $arreglo_f = [];
  //   $emplado_nombre = DB::table('empleados AS e')->
  //   select(DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
  //   ->where('id',$empleado->empleado_asignado_id)
  //   ->first();
  //
  //   $total_s = 0;
  //
  //   for ($i=0; $i < count($arreglo_fechas_buscar); $i++) {
  //     $contratos = DB::table('control_tiempo')
  //     ->leftJoin('contratos AS c', 'c.empleado_id', '=', 'control_tiempo.empleado_asignado_id')
  //     ->select('c.*')
  //     ->where('control_tiempo.fecha',$arreglo_fechas_buscar[$i])
  //     ->where('control_tiempo.empleado_asignado_id',$empleado->empleado_asignado_id)
  //     ->orderBy('id', 'desc')->get();
  //     // $contratos = \App\Contrato::where('empleado_id',$empleado->empleado_asignado_id)->orderBy('id', 'desc')->get();
  //     if (count($contratos) > 0) {
  //       $sueldos = DB::table('sueldos')->where('contrato_id', $contratos[0]->id)->orderBy('id', 'desc')->get();
  //       if (count($sueldos) > 0) {
  //         $total_s += $sueldos[0]->sueldo_diario_real;
  //       }else {
  //         $total_s += 0;
  //       }
  //     }else {
  //       $total_s += 0;
  //     }
  //     // $arreglo_f  = [$total_s];
  //   }
  //   $arreglo [] =[
  //     'empleado' => $emplado_nombre->empleado_nombre,
  //     'data' => $total_s,
  //   ];
  // }
  //
  // $b [] = $arreglo;
  //



  // return response()->json($b);

  // $arreglo = [];
  // $valores = explode('&',$data);
  // if ($valores[0] === 'Proyecto') {
  //   $where = 'ordenes_compras.proyecto_id';
  // }elseif ($valores[0] === 'Proveedor') {
  //   $where = 'ordenes_compras.proveedore_id';
  // }
  // $ordencompra = Compras::
  // join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
  // ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
  // ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
  // 'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
  // ->whereIn($where,$valores)
  // ->get();
  //
  // foreach ($ordencompra as $key => $value) {
  //
  //   $partidas_rhoc_a = requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
  //   ->join('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
  //   // ->leftJoin('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
  //   // ->leftJoin('lotes AS l','l.entrada_id','=','pe.entrada_id')
  //   // ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
  //   // ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
  //   ->select('requisicion_has_ordencompras.*','a.descripcion as description'
  //   // ,'m.tipo_movimiento','m.fecha'
  //   )
  //   ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
  //   // ->where('m.tipo_movimiento','Entrada')
  //   ->get()->toArray();
  //
  //   $partidas_rhoc_s = requisicionhasordencompras::where('requisicion_has_ordencompras.orden_compra_id',$value->id)
  //   ->join('servicios AS s','s.id','=','requisicion_has_ordencompras.servicio_id')
  //   // ->leftJoin('servicio_check AS sc','sc.rhoc_id','=','requisicion_has_ordencompras.id')
  //   ->select('requisicion_has_ordencompras.*','s.nombre_servicio as description'
  //   // ,'sc.fecha'
  //   )
  //   ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')->get()->toArray();
  //
  //   $partidas_rhoc = array_merge($partidas_rhoc_a,$partidas_rhoc_s);
  //
  //   $arr = [];
  //   foreach ($partidas_rhoc as $key => $va) {
  //     if ($va['articulo_id'] != '') {
  //       $entrada = \App\PartidaEntrada::
  //       // ->leftJoin('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
  //       leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
  //       ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
  //       ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
  //       ->where('partidas_entradas.req_com_id',$va['id'])
  //       ->where('m.tipo_movimiento','Entrada')
  //       ->select('m.cantidad','m.fecha','m.id')
  //       ->get();
  //     }
  //     if ($va['servicio_id'] != '') {
  //       $entrada = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
  //       ->select('servicio_check.fecha','rhoc.cantidad','servicio_check.id')->get();
  //     }
  //
  //     if ($va['articulo_id'] != '') {
  //       $salida = \App\PartidaEntrada::
  //       // ->leftJoin('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
  //       leftJoin('lotes AS l','l.entrada_id','=','partidas_entradas.id')
  //       ->leftJoin('lote_almacen AS la','la.lote_id','=','l.id')
  //       ->leftJoin('movimientos AS m','m.lote_id','=','la.id')
  //       ->where('partidas_entradas.req_com_id',$va['id'])
  //       ->where('m.tipo_movimiento','Salida')
  //       ->select('m.cantidad','m.fecha','m.id')
  //       ->get();
  //     }
  //     if ($va['servicio_id'] != '') {
  //       $salida = \App\ServicioCheck::join('requisicion_has_ordencompras AS rhoc','rhoc.id','=','servicio_check.rhoc_id')
  //       ->select('servicio_check.fecha','rhoc.cantidad','servicio_check.id')->get();
  //     }
  //     $arr [] = [
  //       'salida' => $salida,
  //       'entrada' => $entrada,
  //       'descripcion' => $va['description'],
  //       'cantidad' => $va['cantidad'],
  //       // 'id' => $va['articulo_id'],
  //     ];
  //   }
  //
  //
  //
  //
  //
  //   $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
  //   ->where('orden_compra_id',$value->id)->first();
  //
  //   $total_sin_en = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
  //   ->where('orden_compra_id',$value->id)
  //   ->where('articulo_id','!=','NULL')
  //   ->where('condicion','1')->first();
  //
  //   $total_con_en = requisicionhasordencompras::
  //   // join('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
  //   // ->join('lotes AS l','l.entrada_id','=','pe.id')
  //   // ->join('lote_almacen AS la','la.lote_id','=','l.id')
  //   // ->join('lote_temporal AS lt','lt.lote_almacen_id','=','la.id')
  //  select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
  //   // ->select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad) + SUM(lt.cantidad)) AS suma_total_con_en'))
  //   ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
  //   // ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
  //   ->where('requisicion_has_ordencompras.condicion','0')->first();
  //   $total_salidas = 0;
  //
  //   // $total_temp = LoteTemporal::
  //   // Join('lote_almacen AS la','la.id','=','lote_temporal.lote_almacen_id')
  //   // ->Join('lotes AS l','l.id','=','la.lote_id')
  //   // ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
  //   // ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
  //   // ->where('RHOC.orden_compra_id',$value->id)
  //   // ->select(DB::raw('SUM(lote_temporal.cantidad) AS suma_total_partidas_temp'))
  //   // ->first();
  //
  //
  //   // foreach ($total_oc as $key => $valor) {
  //     $partidas = Partidas::
  //     Join('lote_almacen AS la','la.id','=','partidas.lote_id')
  //     ->Join('lotes AS l','l.id','=','la.lote_id')
  //     ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
  //     ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
  //     ->where('RHOC.orden_compra_id',$value->id)
  //     ->select(DB::raw('SUM(partidas.cantidad) AS suma_total_partidas'))
  //     ->first();
  //
  //     $total_con_en_ser = requisicionhasordencompras::
  //    select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en_ser'))
  //     ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
  //     ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')
  //     ->where('requisicion_has_ordencompras.condicion','0')->first();
  //
  //     $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
  //     if ($total_con_en_ser->suma_total_con_en_ser != null) {
  //       $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
  //     }
  //     // $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
  //   // }
  //   $total = $total_oc->suma_total_oc == null ? 0 : $total_oc->suma_total_oc;
  //   $total_con_entradas = $total_con_en->suma_total_con_en == null ? 0 : $total_con_en->suma_total_con_en;
  //   $total_sin_entradas = $total_sin_en->suma_total_sin_en == null ? 0 : $total_sin_en->suma_total_sin_en;
  //
  //
  //
  //   if (($total_con_entradas) == 0) {
  //   $porcentaje = 0;
  //   }else {
  //   $porcentaje = (($total_con_entradas) * 100) / ($total);
  //   }
  //   if (($total_salidas) == 0) {
  //   $porcentaje_salida = 0;
  //   }else {
  //   $porcentaje_salida = (($total_salidas) * 100) / ($total);
  //   }
  //
  //   $facturas_entradas = FacturasEntradas::
  //   where('orden_compra_id',$value->id)
  //   ->where('entrada_id','0')
  //   ->where('entrada_id','')
  //   ->get();
  //
  //   $total_factura = 0;
  //   $facturas_folios = '';
  //   if (count($facturas_entradas) > 0) {
  //     foreach ($facturas_entradas as $key => $vs) {
  //       $total_factura += $vs->total_factura;
  //       $facturas_folios .= $vs->uuid. ' ';
  //     }
  //   }
  //
  //   $diferencia_costos = (floatval(str_replace(',','',$value->total))) - ($total_factura);
  //
  //   $pagos_compras = PagoCompra::
  //   join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
  //   ->select(DB::raw('SUM(cargo) AS total_pagos'))
  //   ->where('pnr.ordenes_comp_id',$value->id)->first();
  //
  //   $pagos_compras_pagos = PagoCompra::
  //   join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
  //   ->select('pagos_compras.cargo','pagos_compras.tipo_cambio',
  //   DB::raw('(pagos_compras.cargo * pagos_compras.tipo_cambio) AS total_pesos'))
  //   ->where('pnr.ordenes_comp_id',$value->id)->get();
  //
  //   $tipo_change = '';
  //   $pagos_c = '';
  //   $total_en_mn = 0;
  //   foreach ($pagos_compras_pagos as $key => $vp) {
  //     $tipo_change.=round($vp->tipo_cambio,2).','.PHP_EOL;
  //     $pagos_c.=round($vp->cargo,2).','.PHP_EOL;
  //     $total_en_mn += $vp->total_pesos;
  //   }
  //
  //   $total_pagos = $pagos_compras->total_pagos == null ? 0 :$pagos_compras->total_pagos ;
  //
  //   $tipo_cambio = '';
  //
  //   if (($total_pagos) == 0) {
  //   $porcentaje_pago = 0;
  //   }else {
  //   if ($value->moneda == 1) {
  //     $tipo_cambio = $total_pagos / (floatval(str_replace(',','',$value->total)));
  //     }
  //   }
  //
  //   if (($total_pagos) == 0) {
  //   $porcentaje_pago = 0;
  //   }else {
  //   $porcentaje_pago = (($total_pagos) * 100) / ((floatval(str_replace(',','',$value->total))));
  //   }
  //
  //   $folio_oc = '';
  //   if ($value->folio != '') {
  //     $valores = explode('-',$value->folio);
  //     if (count($valores) == 5) {
  //       $folio_oc = $valores[3].'-'.$valores[4];
  //     }
  //   }
  //
  //   if($value->moneda == 1){ $moneda = 'USD';}
  //   if($value->moneda == 2){ $moneda = 'MXN';}
  //
  //
  //   $arreglo [] = [
  //     'oc' => $value,
  //     'partidas' => $arr,
  //     'folio_oc' => $folio_oc,
  //     'total_par' => $total,
  //     'moneda' => $moneda,
  //     'total_sin_entrada' => $total_sin_entradas,
  //     'total_con_entrada' => $total_con_entradas,
  //     'procentaje_entrada' => $porcentaje,
  //     'total_salidas' => $total_salidas,
  //     'porcentaje_salidas' => $porcentaje_salida,
  //     'total_factura' => $total_factura,
  //     'factura' => $facturas_folios,
  //     'diferencia_costos' => $diferencia_costos,
  //     'totales' => $pagos_compras_pagos,
  //     'tipo_cambio' => $tipo_change,
  //     'pagos' => $pagos_c,
  //     'total_en_mn' => $total_en_mn,
  //     'porcentaje_pago' => $porcentaje_pago,
  //   ];
  // }
  //
  // return response()->json($arreglo);
}

public function requisiciones()
{
  try {



    $arreglo = [];
    $requiciones = RequisicionDBP::
    join('empleados AS es','es.id','=','requisiciones.solicita_empleado_id')
    ->join('empleados AS ea','ea.id','=','requisiciones.autoriza_empleado_id')
    ->join('empleados AS ev','ev.id','=','requisiciones.valida_empleado_id')
    ->where('proyecto_id','88')
    ->where('folio','NOT LIKE','%SR')
    ->select('requisiciones.*','es.nombre AS esn','es.ap_paterno AS esp','es.ap_materno AS esm',
    'ea.nombre AS ean','ea.ap_paterno AS eap','ea.ap_materno AS eam',
    'ev.nombre AS evn','ev.ap_paterno AS evp','ev.ap_materno AS evm')
    ->get();

    foreach ($requiciones as $key => $value) {
      $solicita_empleado =  Empleado::where('nombre',$value->esn)->where('ap_paterno',$value->esp)->where('ap_materno',$value->esm)->first();
      $autoriza_empleado =  Empleado::where('nombre',$value->ean)->where('ap_paterno',$value->eap)->where('ap_materno',$value->eam)->first();
      $valida_empleado =  Empleado::where('nombre',$value->evn)->where('ap_paterno',$value->evp)->where('ap_materno',$value->evm)->first();

      $req = new Requisicion();
      $req->folio = $value->folio;
      $req->area_solicita_id = $value->area_solicita_id;
      $req->formato_requisiciones = $value->formato_requisiciones;
      $req->fecha_solicitud = $value->fecha_solicitud;
      $req->descripcion_uso = $value->descripcion_uso;
      $req->tipo_compra = $value->tipo_compra;
      $req->proyecto_id = $value->proyecto_id;
      $req->estado_id = $value->estado_id;//sin requisicion
      $req->condicion = $value->condicion;
      $req->solicita_empleado_id =$solicita_empleado->id;//cambiar por compras
      $req->autoriza_empleado_id = $autoriza_empleado->id;
      $req->valida_empleado_id = $valida_empleado->id;
      Utilidades::auditar($req, $req->id);
      $req->save();

      $par_req = PartidasReDBP::where('requisicione_id',$value->id)->get();

      foreach ($par_req as $key => $valor) {

        $articulo_id = NULL;
        $servicio_id = NULL;

        if ($valor->articulo_id != null ) {
          $articulo_csct = ArticuloDBP::where('id',$valor->articulo_id)->first();
          $articulo_con = Articulo::where('nombre','like','%'.$articulo_csct->nombre.'%')->first();
          $articulo_id = (isset($articulo_con) == true) ? $articulo_con->id : $this->crearArticulo($articulo_csct);
        }

        if ($valor->servicio_id != null ) {
          $servicio_csct = CatServiciosDBP::where('id',$valor->servicio_id)->first();
          $servicio_con = CatServicios::where('nombre_servicio','like','%'.$servicio_csct->nombre_servicio.'%')->first();
          $servicio_id = (isset($servicio_con) == true) ? $servicio_con->id : $this->crearServicio($servicio_csct);
        }

        $partidaRe = new PartidaRe();
        $partidaRe->requisicione_id = $req->id;
        $partidaRe->articulo_id =  $articulo_id;
        $partidaRe->servicio_id = $servicio_id;
        $partidaRe->vehiculo_id =  null ;
        $partidaRe->peso = $valor->peso;
        $partidaRe->cantidad = $valor->cantidad;
        $partidaRe->equivalente = $valor->equivalente;
        $partidaRe->fecha_requerido = $valor->fecha_solicitud;
        $partidaRe->comentario = $valor->comentario;
        $partidaRe->cantidad_compra = $valor->cantidad_compra;
        $partidaRe->cantidad_almacen = $valor->cantidad_almacen;
        $partidaRe->pda = $valor->pda;
        $partidaRe->condicion = $valor->condicion;
        Utilidades::auditar($partidaRe, $partidaRe->pda);
        $partidaRe->save();

      }


    }
    return response()->json(array('status' => true));

    // return response()->json($arreglo);
  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }

}


public function repe()
{
  try {



    $compras_c = Compras::where('folio','like','%GAS%')->first();
    $compras_p = ComprasDBP::where('folio','like','%GAS%')
    ->join('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
    ->leftJoin('empleados AS ee','ee.id','=','ordenes_compras.elabora_empleado_id')
    ->leftJoin('empleados AS ea','ea.id','=','ordenes_compras.autoriza_empleado_id')
    ->select('ordenes_compras.*','ee.nombre AS n_ee','ee.ap_paterno AS ap_ee','ee.ap_materno AS am_ee',
    'ea.nombre AS n_ea','ea.ap_paterno AS ap_ea','ea.ap_materno AS am_ea','p.nombre AS nombre_proveedor','p.razon_social')
    ->get();

    foreach ($compras_p as $key => $value) {

      $proveedor = Proveedor::where('nombre','like',$value->nombre_proveedor)->where('razon_social','like',$value->razon_social)->first();
      $proveedor_id = (isset($proveedor) == true) ? $proveedor->id : $this->crearProveedor($value->proveedore_id);

      $empleado_uno = Empleado::where('nombre',$value->n_ee)->where('ap_paterno',$value->ap_ee)->where('ap_materno',$value->am_ee)->first();
      $empleado_dos = Empleado::where('nombre',$value->n_ea)->where('ap_paterno',$value->ap_ea)->where('ap_materno',$value->am_ea)->first();

      $elabora_empleado_id = (isset($empleado_uno) == true) ? $empleado_uno->id : 0;
      $autoriza_empleado_id = (isset($empleado_dos) == true) ? $empleado_dos->id : 0;

      //hacer una busqueda de empleados, proveedores y articulos
      $compras_new = new Compras();
      $compras_new->folio = $value->folio;
      $compras_new->proyecto_id = 90;//
      $compras_new->condicion_pago_id = $value->condicion_pago_id;
      $compras_new->periodo_entrega = $value->periodo_entrega;
      $compras_new->fecha_orden = $value->fecha_orden;
      $compras_new->lugar_entrega = $value->lugar_entrega;
      $compras_new->observaciones = $value->observaciones;
      $compras_new->descuento = $value->descuento;
      $compras_new->total = $value->total;
      $compras_new->tipo_cambio = $value->tipo_cambio;
      $compras_new->moneda = $value->moneda;
      $compras_new->referencia = $value->referencia;
      $compras_new->cie = $value->cie;
      $compras_new->sucursal = $value->sucursal;
      $compras_new->proveedore_id = $proveedor_id;//
      $compras_new->elabora_empleado_id = $elabora_empleado_id;//
      $compras_new->autoriza_empleado_id = $autoriza_empleado_id;//
      $compras_new->comentario_condicion_pago = $value->comentario_condicion_pago;
      $compras_new->conrequisicion = $value->conrequisicion;
      Utilidades::auditar($compras_new, $compras_new->id);
      $compras_new->save();

      $partidas_compras = RequisicionhasordencomprasDBP::where('orden_compra_id',$value->id)->get();
      foreach ($partidas_compras as $key => $valor) {
        $articulo_id = NULL;
        $servicio_id = NULL;

        if ($valor->articulo_id != null ) {
          $articulo_csct = ArticuloDBP::where('id',$valor->articulo_id)->first();
          $articulo_con = Articulo::where('nombre','like','%'.$articulo_csct->nombre.'%')->first();
          $articulo_id = (isset($articulo_con) == true) ? $articulo_con->id : $this->crearArticulo($articulo_csct);
        }

        if ($valor->servicio_id != null ) {
          $servicio_csct = CatServiciosDBP::where('id',$valor->servicio_id)->first();
          $servicio_con = CatServicios::where('nombre_servicio','like','%'.$servicio_csct->nombre_servicio.'%')->first();
          $servicio_id = (isset($servicio_con) == true) ? $servicio_con->id : $this->crearServicio($servicio_csct);
        }

        $this->GuardarRequisicion($valor,$compras_new,$articulo_id,$servicio_id);

      }



    }

    // $compras_f = array_merge($compras_c,$compras_p);

    return response()->json(array('status' => true));

  }catch (\Throwable $e) {
    Utilidades::errors($e);

  }

}

public function crearProveedor($id)
{
  $proveedor_dbp = ProveedorDBP::where('id',$id)->first();

  $proveedor = new Proveedor();
  $proveedor->nombre = $proveedor_dbp->nombre;
  $proveedor->razon_social = $proveedor_dbp->razon_social;
  $proveedor->direccion = $proveedor_dbp->direccion;
  $proveedor->banco_transferencia = $proveedor_dbp->banco_transferencia;
  $proveedor->numero_cuenta = $proveedor_dbp->numero_cuenta;
  $proveedor->clabe = $proveedor_dbp->clabe;
  $proveedor->dia_credito = $proveedor_dbp->dia_credito;
  $proveedor->limite_credito = $proveedor_dbp->limite_credito;
  $proveedor->categoria = $proveedor_dbp->categoria;
  $proveedor->condicion_pago = $proveedor_dbp->condicion_pago;
  $proveedor->giro = $proveedor_dbp->giro;
  $proveedor->rfc = $proveedor_dbp->rfc;
  $proveedor->ciudad = $proveedor_dbp->ciudad;
  $proveedor->estado = $proveedor_dbp->estado;
  $proveedor->contacto = $proveedor_dbp->contacto;
  $proveedor->telefono = $proveedor_dbp->telefono;
  $proveedor->correo = $proveedor_dbp->correo;
  $proveedor->pagina = $proveedor_dbp->pagina;
  $proveedor->descripcion = $proveedor_dbp->descripcion;
  $proveedor->tipo_moneda = $proveedor_dbp->tipo_moneda;
  $proveedor->condicion = $proveedor_dbp->condicion;
  $proveedor->tipo_cambio = $proveedor_dbp->tipo_cambio;
  // $proveedor->fill($proveedor_dbp->all());
  Utilidades::auditar($proveedor, $proveedor->id);
  $proveedor->save();

  return $proveedor->id;

}

public function crearArticulo($data)
{
  $articulo = new Articulo();
  $articulo->grupo_id = $data->grupo_id;
  $articulo->tipo_resguardo_id=$data->trid;
  $articulo->calidad_id = $data->calidad_id;
  $articulo->codigo = $data->codigo;
  $articulo->nombre = $data->nombre;
  $articulo->descripcion = $data->descripcion;
  $articulo->nombreproveedor = $data->nombreproveedor;
  $articulo->marca = $data->marca;
  $articulo->unidad = $data->unidad;
  $articulo->comentarios = $data->comentarios;
  $articulo->minimo = $data->minimo;
  $articulo->maximo = $data->maximo;
  $articulo->condicion = '1';
  $articulo->centro_costo_id = $data->centro_costo_id;
  Utilidades::auditar($articulo, $articulo->id);
  $articulo->codigo_producto=$data->codigoProductoSat;
  $articulo->save();

  return $articulo->id;
}

public function crearServicio($data)
{
  $servicio = new CatServicios();
  $servicio->nombre_servicio = $data->nombre_servicio;
  $servicio->proveedor_marca = $data->proveedor_marca;
  $servicio->unidad_medida = $data->unidad_medida;
  Utilidades::auditar($servicio, $servicio->id);
  $servicio->save();
  return $servicio->id;
}


public function GuardarRequisicion($valor,$compras_new,$articulo_id,$servicio_id){
  try {

    // $ordencompra = Compras::where('id','=',$request->orden_compra_id)->first();

    $requi = Requisicion::where('descripcion_uso','=','ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$compras_new->folio)
    ->where('estado_id','=','11')->first();

    if ($requi == null) {
      $req = new Requisicion();
      $req->folio = $compras_new->folio.'-SR';
      $req->area_solicita_id = 1;
      $req->formato_requisiciones = '---';
      $req->fecha_solicitud = $compras_new->fecha_orden;
      $req->descripcion_uso = 'ORDEN DE COMPRA SIN REQUISCION CON FOLIO: '.$compras_new->folio;
      $req->tipo_compra = 1;
      $req->proyecto_id = $compras_new->proyecto_id;
      $req->estado_id = 11;//sin requisicion
      $req->condicion = 2;
      $req->solicita_empleado_id = $compras_new->elabora_empleado_id;//cambiar por compras
      $req->autoriza_empleado_id = $compras_new->autoriza_empleado_id;
      $req->valida_empleado_id = $compras_new->elabora_empleado_id;
      Utilidades::auditar($req, $req->id);
      $req->save();
      $result =  $this->llenarPartidaRe($req, $valor, $compras_new,$articulo_id,$servicio_id);
    }
    else {
      $result =  $this->llenarPartidaRe($requi, $valor, $compras_new,$articulo_id,$servicio_id);
    }
    $this->compraEC($result,$valor,$compras_new,$articulo_id,$servicio_id);
    return true;
  } catch (\Throwable $e) {
    Utilidades::errors($e);
  }

}

public function llenarPartidaRe($req, $valor, $compras_new,$articulo_id,$servicio_id){
  $partidaRe = new PartidaRe();
  $partidaRe->requisicione_id = $req->id;
  $partidaRe->articulo_id =  $articulo_id;
  $partidaRe->servicio_id = $servicio_id;
  $partidaRe->vehiculo_id =  null ;
  $partidaRe->peso = 0;
  $partidaRe->cantidad = $valor->cantidad;
  $partidaRe->equivalente = 0;
  $partidaRe->fecha_requerido = $req->fecha_solicitud;
  $partidaRe->comentario = $valor->comentario;
  $partidaRe->cantidad_compra = $valor->cantidad;
  $partidaRe->cantidad_almacen = 0;
  $partidaRe->pda = $pda = Utilidades::crearPda($req->id);
  $partidaRe->condicion = 11;
  Utilidades::auditar($partidaRe, $partidaRe->pda);
  $partidaRe->save();



  return $partidaRe;
}


public function compraEC($result,$valor,$compras_new,$articulo_id,$servicio_id)
{
  $requi = new Requisicionhasordencompras();
  $requi->requisicione_id = $result->requisicione_id;
  $requi->orden_compra_id = $compras_new->id;
  $requi->articulo_id = $articulo_id;
  $requi->servicio_id = $servicio_id;
  $requi->vehiculo_id = null;
  $requi->cantidad = $valor->cantidad;
  $requi->precio_unitario = $valor->precio_unitario;
  $requi->comentario = $valor->comentario;
  $requi->cantidad_entrada = $valor->cantidad;
  Utilidades::auditar($requi, $requi->pda);
  $requi->save();

  if ($valor->adicionales > 0) {
    $adicionales = new Adicionales();
    $adicionales->req_has_comp = $requi->id;
    $adicionales->cantidad = $valor->adicionales;
    Utilidades::auditar($adicionales, $adicionales->id);
    $adicionales->save();
  }
  // $this->condicion($result->requisicione_id,$result->pda,$request->cantidad);
  if($valor->centro_costo_id != ''){
    $costo_partida_compra = new CostosProyectosServicios();
    $costo_partida_compra->catalogo_centro_costos_id = $valor->centro_costo_id;
    $costo_partida_compra->requisicion_has_ordencompra_id = $requi->id;
    $costo_partida_compra->save();
  }
  return true;
  // code...
}


}
