<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compras;
use App\Proyecto;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use \App\Http\Helpers\Utilidades;
use App\CostosProyectosAdmin;
use App\CostosProyectosServicios;
use App\Exports\ComprasExport;
use App\Exports\ComprasTrazabilidadExport;
use App\Exports\ComprasTrazabilidadTotalesExport;
use App\Exports\ComprasHistoricoExport;
use Maatwebsite\Excel\Facades\Excel;
use App\requisicionhasordencompras;
use App\RequisicionhasordencomprasDBP;
use App\Partidas;
use App\FacturasEntradas;
use App\PagoCompra;
use App\Exports\ComprasTrazabilidadGeneralExport;
use Barryvdh\DomPDF\Facade as PDF;
use App\PartidaRe;
use App\Http\Controllers\ComprasCSCTController;
use App\PartidasDatosBancariosOC;



class ComprasController extends Controller
{
  /**
  * [protected Reglas para el guardado y actualizacion en la BD]
  * @var [type]
  */
  protected $rules = array(
    // 'folio' => 'required|max:30',
    'condicion_pago_id' => 'required|max:30',
    'periodo_entrega' => 'required|max:50',
    'lugar_entrega' => 'required|max:125',
    'proyecto_id' => 'required',
    'proveedore_id' => 'required',
    'moneda' => 'required',
    // 'referencia' => 'required|max:30',
  );

  /**
  * [index consulta de la tabla requisicion_has_ordencompras]
  * @param  Request $request [description]
  * @param  [int]  $id      [description]
  * @return [Response]           [description]
  */
  public function index(Request $request, $id)
  {
    $proyecto = Compras::join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
    ->where('ordenes_compras.id','=',$id)->first();
    $compras_vehi = [];
    $compras_art = DB::table('requisicion_has_ordencompras')
    ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('articulos AS a', 'a.id', '=', 'requisicion_has_ordencompras.articulo_id')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
    ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
    ->join('partidas_requisiciones', function ($join){
      $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
      ->on('requisicion_has_ordencompras.articulo_id','=','partidas_requisiciones.articulo_id');
    })
    ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),'req.id AS rid',
    'req.folio AS rf','partidas_requisiciones.comentario','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton','a.id AS aid','a.descripcion AS ad','CPS.catalogo_centro_costos_id')
    ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
    ->where('requisicion_has_ordencompras.articulo_id','!=','null')
    ->get()->toArray();

    $compras_serv = DB::table('requisicion_has_ordencompras')
    ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('servicios AS s', 's.id', '=', 'requisicion_has_ordencompras.servicio_id')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
    ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
    ->join('partidas_requisiciones', function ($join){
      $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
      ->on('requisicion_has_ordencompras.servicio_id','=','partidas_requisiciones.servicio_id');
    })
    ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
    'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
    's.id AS aid','partidas_requisiciones.comentario','s.nombre_servicio AS ad','CPS.catalogo_centro_costos_id')
    ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
    ->where('requisicion_has_ordencompras.servicio_id','!=','null')
    ->get()->toArray();

    if ($proyecto->nombre_corto === 'MANTENIMIENTO VEHICULAR') {
      $compras_vehi = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('cat_mantenimiento_vehiculos AS v', 'v.id', '=', 'requisicion_has_ordencompras.vehiculo_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      'v.id AS aid','v.descripcion AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.vehiculo_id','!=','null')
      ->get()->toArray();
      // $compras = array_merge($compras_art,$compras_serv,$compras_vehi);

    }elseif ($proyecto->nombre_corto === 'VEHÍCULOS') {
      $compras_vehi = DB::table('requisicion_has_ordencompras')
      ->leftJoin('requisiciones AS req', 'req.id', '=', 'requisicion_has_ordencompras.requisicione_id')
      ->leftJoin('ordenes_compras AS oc', 'oc.id' , '=', 'requisicion_has_ordencompras.orden_compra_id')
      ->leftJoin('vehiculos AS v', 'v.id', '=', 'requisicion_has_ordencompras.vehiculo_id')
      ->leftJoin('proyectos AS p', 'p.id', '=', 'req.proyecto_id')
      ->leftJoin('costos_proyectos_servicios AS CPS','CPS.requisicion_has_ordencompra_id','=','requisicion_has_ordencompras.id')
      ->select('requisicion_has_ordencompras.*',DB::raw('requisicion_has_ordencompras.cantidad * requisicion_has_ordencompras.precio_unitario as total'),
      'req.id AS rid','req.folio AS rf','req.fecha_solicitud AS rfs','p.nombre_corto AS proyecton',
      'v.id AS aid','v.descripcion AS ad','CPS.catalogo_centro_costos_id')
      ->where('requisicion_has_ordencompras.orden_compra_id', '=', $id)
      ->where('requisicion_has_ordencompras.vehiculo_id','!=','null')
      ->get()->toArray();
      // $compras = array_merge($compras_art,$compras_serv,$compras_vehi);

    }

    $compras = array_merge($compras_art,$compras_serv,$compras_vehi);

    $total = $this->ObtenerTotal($id);
    $ordenCompra = \App\Compras::findOrFail($id);
    $ordenCompra->total = $total;
    $ordenCompra->total_aux = (floatval(str_replace(',','',$total)));
    $ordenCompra->save();

    return response()->json($compras);
  }

  /*
  * Función que recibe un parámetro id y retorna un total, que es la suma de los precios de los articulos de cada partida y sus respectivos impuestos
  * pertenecientes a la orden de compra del id que recibe
  */
  /**
  * [ObtenerTotal description]
  * @param [type] $id [description]
  */
  public function ObtenerTotal($id)
  {
    $subtotal_info = 0;
    $subtotal_info_dos = 0;
    $suma_partidas_compras = \App\requisicionhasordencompras::where('orden_compra_id','=',$id)->
    select(DB::raw("SUM(cantidad * precio_unitario) AS subtotal"))->first();
    $subtotal_info_dos = $suma_partidas_compras->subtotal;

    $orden_compras  = \App\Compras::select('descuento')->where('id',$id)->first();

    if($orden_compras->descuento > 0){
      $subtotal_info = (($suma_partidas_compras->subtotal/100)*$orden_compras->descuento);
      $subtotal_info_dos =  $suma_partidas_compras->subtotal - (($suma_partidas_compras->subtotal/100)*$orden_compras->descuento);
    }

    $impuestos = \App\Impuesto::where('orden_compra_id','=',$id)->get();

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

      }
    }
    $total = ($subtotal_info_dos + array_sum($totalesimpuestos)) - array_sum($totalesimpuestos_retenidos);
    $total = number_format($total, 2);
    return $total;
  }

  /*
  * Función que recibe un parámetro id y retorna un arreglo en el cual se asocia el total de la compra consultada y el proveedor de la compra
  * pertenecientes a la orden de compra del id que recibe
  */
  public function ConsultarTotal($id)
  {
    $compra = Compras::where('id','=',$id)->first();
    $proveedor = \App\Proveedor::where('id','=',$compra->proveedore_id)->first();
    $arreglo [] = [
      'compra' => $this->ObtenerTotal($id),
      'limitecredito' => $proveedor->limite_credito,
    ];
    return response()->json($arreglo);
  }

  /*
  * Función que recibe un request donde es acedido y envia parametro del post para poder descargar el archivo que se esta solicitando en la peticiónote
  * y guardarlo de manera local
  */
  public function update(Request $request)
  {
    if ($request->metodo == 1) {
      // Se obtiene el archivo del  serve

      $archivo = Utilidades::ftpSolucion($request->archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('descarga')->put($request->archivo, $archivo);
      //--Devuelve la respuesta y descarga el archivo--//
      return response()->download(storage_path().'/app/descargas/'.$request->archivo);
    }

    if ($request->metodo == 0) {
      //elimina de la ruta local el archivo descargado
      Storage::disk('descarga')->delete($request->archivo);
      Storage::disk('local')->delete($request->archivo);
    }
  }

  /*
  * Función que recibe un parámetro que es una petición request por la cual es accedido y se envian los parametro del post retornando una status true
  * si las condiciones son cumplidas correctamente, en este caso el de agregar o actualizar una compra, dependiendo del metodo que se envia en el request
  * ademas de agregar impuestos si se tiene, guardar la compra como paga no recurrente y de guardar el formato correspondiente solo en la opción de actualizar
  */
  public function store(Request $request)
  {
    try {

      $proyecto = Proyecto::findOrFail($request->proyecto_id);
      if ($proyecto->condicion != 1) {
        return response()->json(array(
          'status' => false,
          'errors' => ['El proyecto no esta activo.']
        ));
      }

      $tipos = explode(',',$request->tipos);
      $porcentaje = explode(',',$request->porcentaje);
      $retenido = explode(',',$request->retenido);
      if (!$request->ajax()) return redirect('/');

      $validator = Validator::make($request->all(), $this->rules);

      if ($validator->fails()) {
        return response()->json(array(
          'status' => false,
          'errors' => $validator->errors()->all()
        ));
      }

      if($request->metodo == "Nuevo"){
        DB::beginTransaction();
        // Obtener el ID del proyecto
        $folio = Utilidades::getFolio('OC-CONSERFLOW', $request->proyecto_id);
        $compra = new Compras();
        $compra->folio = $folio;
        $compra->proyecto_id = $request->proyecto_id;
        $compra->condicion_pago_id = $request->condicion_pago_id;
        $compra->periodo_entrega = $request->periodo_entrega;
        $compra->fecha_orden = $request->fecha_orden;
        $compra->lugar_entrega = $request->lugar_entrega;
        $compra->observaciones = $request->observaciones;
        $compra->descuento = $request->descuento;
        $compra->total = $request->total;
        $compra->tipo_cambio = $request->tipo_cambio;
        $compra->moneda = $request->moneda;
        $compra->referencia = $request->referencia;
        $compra->cie = $request->cie;
        $compra->sucursal = $request->sucursal;
        $compra->proveedore_id = $request->proveedore_id;
        $compra->proveedore_csct_id = $request->proveedore_csct_id == '' ? $request->proveedore_id :  $request->proveedore_csct_id;
        $compra->elabora_empleado_id = $request->elabora_empleado_id;
        $compra->autoriza_empleado_id = $request->autoriza_empleado_id;
        $compra->comentario_condicion_pago = $request->comentario_condicion_pago;
        $compra->conrequisicion = $request->conrequisicion;
        if ($request->fecha_probable_pago != '') {
          $compra->fecha_probable_pago = $request->fecha_probable_pago;
          $compra->prioridad = $request->prioridad;
        }
        Utilidades::auditar($compra, $compra->id);
        $compra->save();

        $pdboc = new PartidasDatosBancariosOC();
        $pdboc->banco = $request->banco;
        $pdboc->clabe = $request->clabe;
        $pdboc->cuenta = $request->cuenta;
        $pdboc->orden_compra_id = $compra->id;
        $pdboc->titular = $request->titular;
        $pdboc->save();
        //
        // if () {
        //   // code...
        // }

        for ($i=0; $i < $request->tamanio; $i++) {
          $impueto = new \App\Impuesto();
          $impueto->orden_compra_id = $compra->id;
          $impueto->tipo = $tipos[$i];
          $impueto->porcentaje = $porcentaje[$i];
          $impueto->retenido = $retenido[$i];
          Utilidades::auditar($impueto, $impueto->id);
          $impueto->save();
        }

        $total = $this->ObtenerTotal($compra->id);
        $ordenCompra_tot = \App\Compras::findOrFail($compra->id);
        $ordenCompra_tot->total = $total;
        $ordenCompra_tot->total_aux = (floatval(str_replace(',','',$total)));
        $ordenCompra_tot->save();

        $this->guardarPagosNoRecurrentes($request, $compra->id);
        DB::commit();
        return response()->json(array(
          'status' => true,
          'CONTENIDO' => $request->cotizacionesID,
          'compra' => $compra,
        ));
      }

      if($request->metodo == "Actualizar"){
        DB::beginTransaction();
        /*ACTUALIZAR REGISTRO*/
        //Variables de archivo
        $FacturaStore=null;
        //*Variables para actualizar nuevos archivos y eliminar existentes
        $ValorFactura = null;
        $compras = Compras::where('id',$request->id)->get();

        foreach ($compras as $key => $item) {
          $ValorFactura = $item->ordenes_formato;
          $FacturaStore=$item->ordenes_formato;
        }
        //*FIN

        //--Si el request no contiene archivos, solo se actualizan los campos listados--//
        if(!$request->hasFile('ordenes_formato'))
        {

          $ordenCompra = Compras::findOrFail($request->id);
          $ordenCompra->folio = $request->folio;
          $ordenCompra->ordenes_formato = $FacturaStore;
          $ordenCompra->condicion_pago_id = $request->condicion_pago_id;
          $ordenCompra->periodo_entrega = $request->periodo_entrega;
          $ordenCompra->fecha_orden = $request->fecha_orden;
          $ordenCompra->lugar_entrega = $request->lugar_entrega;
          $ordenCompra->observaciones = $request->observaciones === 'null' ? NULL : $request->observaciones;
          $ordenCompra->descuento = $request->descuento;
          $ordenCompra->tipo_cambio = $request->tipo_cambio;
          $ordenCompra->moneda = $request->moneda;
          $ordenCompra->referencia = $request->referencia === 'null' ? NULL : $request->referencia;
          $ordenCompra->cie = $request->cie === 'null' ? NULL : $request->cie;
          $ordenCompra->sucursal = $request->sucursal === 'null' ? NULL : $request->sucursal;
          $ordenCompra->proveedore_id = $request->proveedore_id;
          $ordenCompra->proveedore_csct_id = $request->proveedore_csct_id == '' ? $request->proveedore_id :  $request->proveedore_csct_id;
          // $ordenCompra->proveedore_csct_id = $request->proveedore_csct_id;
          $ordenCompra->estado_id = $request->estado_id;
          $ordenCompra->elabora_empleado_id = $request->elabora_empleado_id;
          $ordenCompra->autoriza_empleado_id = $request->autoriza_empleado_id;
          $ordenCompra->comentario_condicion_pago = $request->comentario_condicion_pago === 'null' ? NULL : $request->comentario_condicion_pago;
          if ($request->fecha_probable_pago != '') {
            $ordenCompra->fecha_probable_pago = $request->fecha_probable_pago;
            $ordenCompra->prioridad = $request->prioridad;
          }
          Utilidades::auditar($ordenCompra, $ordenCompra->id);
          $ordenCompra->save();

          $pdboc_ = PartidasDatosBancariosOC::where('orden_compra_id',$request->id)->first();
          // $pdboc_ = new PartidasDatosBancariosOC();
          if (isset($pdboc_) == true) {
            // code...
          $pdboc_->banco = $request->banco;
          $pdboc_->clabe = $request->clabe;
          $pdboc_->cuenta = $request->cuenta;
          $pdboc_->orden_compra_id = $request->id;
          $pdboc_->titular = $request->titular;
          Utilidades::auditar($pdboc_, $pdboc_->id);
          $pdboc_->update();
          }
          // return response()->json($pdboc_);

          for ($i=0; $i < $request->tamanio; $i++) {
            $impueto = new \App\Impuesto();
            $impueto->orden_compra_id = $ordenCompra->id;
            $impueto->tipo = $tipos[$i];
            $impueto->porcentaje = $porcentaje[$i];
            $impueto->retenido = $retenido[$i];
            Utilidades::auditar($impueto, $impueto->id);
            $impueto->save();
          }

          $total = $this->ObtenerTotal($ordenCompra->id);
          $ordenCompra_tot = \App\Compras::findOrFail($ordenCompra->id);
          $ordenCompra_tot->total = $total;
          $ordenCompra_tot->total_aux = (floatval(str_replace(',','',$total)));
          $ordenCompra_tot->save();

          $this->guardarPagosNoRecurrentes($request, $ordenCompra->id);

          DB::commit();
          return response()->json(array(
            'status' => true,
            // 'cuenta' => count($cotizaciones),
            'observaciones'=>$request->cie
          ));
        }
      }
    }catch (\Throwable $e) {
      DB::rollBack();
      Utilidades::errors($e);
      return response()->json(array('status' => 'error', "mensaje"=>$e->getMessage()));
    }


  }

  public function subFactura(Request $request)
  {
    $FacturaStore=null;
    //*Variables para actualizar nuevos archivos y eliminar existentes
    $ValorFactura = null;
    $compras = Compras::where('id',$request->id)->get();

    foreach ($compras as $key => $item) {
      $ValorFactura = $item->ordenes_formato;

      $FacturaStore=$item->ordenes_formato;
    }
    //*FIN

    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file('ordenes_formato')->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file('ordenes_formato')->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = $FacturaNombre.'_'.uniqid().'.'.$FacturaExt;
    //Subida del archivo al servidor ftp
    Storage::disk('local')->put('Archivos/'.$FacturaStore, fopen($request->file('ordenes_formato'), 'r+'));
    if ($ValorFactura != null) {
      //Elimina el archivo en el servidor si requiere ser actualizado
      Utilidades::ftpSolucionEliminar($ValorFactura);
    }
    $ordenCompra = Compras::findOrFail($request->id);
    $ordenCompra->ordenes_formato = $FacturaStore;
    $ordenCompra->save();

    return response()->json(array(
      'status' => true,
    ));
  }


  /*
  * Función que recibe dos parámetros un objeto y un id y retorna un status true si las condiciones son cumplidas correctamente en este
  * caso se guarda en la tabla pagos_no_recurrentes con la condicion del tipo de pago credito o contado
  */
  public function guardarPagosNoRecurrentes($request, $id)
  {
    $rango = 0;
    $pagos = 1;
    if ($request->condicion_pago_id == 2) {
      $rango = 0;
      $pagos = 1;
    }
    elseif ($request->condicion_pago_id == 1) {
      $rango = $request->rango_dias;
      $pagos = $request->pagos;

      // $this->guardarFechas($request->fechas);
    }
    $pagosNoRecurrentes = \App\PagosNoRecurrentes::where('ordenes_comp_id','=',$id)->first();
    if ($pagosNoRecurrentes != null) {
      $pagosNoRecurrentes->proveedor_id = $request->proveedore_id;
      $pagosNoRecurrentes->ordenes_comp_id = $id;
      $pagosNoRecurrentes->proyecto_id = $request->proyecto_id;
      $pagosNoRecurrentes->monto = 0;
      // $pagosNoRecurrentes->rango_dias = $rango == null ? 0 : $rango == 'null' ? 0 : $rango;
      $n=0;
      if($rango!=null && $rango!="null") $n=$rango;
      $pagosNoRecurrentes->rango_dias=$n;
      $pagosNoRecurrentes->eventos = $pagos;
      Utilidades::auditar($pagosNoRecurrentes, $pagosNoRecurrentes->id);
      $pagosNoRecurrentes->save();
    }
    else {
      $pagosNoRecurrentesNuevo = new \App\PagosNoRecurrentes();
      $pagosNoRecurrentesNuevo->proveedor_id = $request->proveedore_id;
      $pagosNoRecurrentesNuevo->ordenes_comp_id = $id;
      $pagosNoRecurrentesNuevo->monto = 0;
      $n=0;
      if($rango!=null && $rango!="null") $n=$rango;
      $pagosNoRecurrentesNuevo->rango_dias=$n;
      // $pagosNoRecurrentesNuevo->rango_dias = $rango == null ? 0 : $rango == 'null' ? 0 : $rango;
      $pagosNoRecurrentesNuevo->eventos = $pagos;
      Utilidades::auditar($pagosNoRecurrentesNuevo, $pagosNoRecurrentesNuevo->id);
      $pagosNoRecurrentesNuevo->save();
    }
    return response()->json(array('status' =>true));
  }

  public function fechas($value='')
  {
    // code...
  }
  /*
  * Función que recibe un parametro id y retorna un status true si las condiciones son ejecutadas correctamente
  */
  public function edit($id)
  {
    $compra = Compras::findOrFail($id);
    if ($compra->condicion==0) {
      $compra->condicion = 1;
    }else{
      $compra->condicion = 0;
    }
    $compra->update();
    return response()->json(array('status' =>true));
  }

  /*
  * Función que recibe un objeto request que contiene la ruta de acceso a ala funcion que retorna un arreglo de la consulta de las
  * todas las compras que existen
  */
  public function show($id)
  {
    $arreglo = [];
    $compras = Compras::orderBy('id', 'asc')
    ->join('proveedores AS p', 'p.id', '=', 'ordenes_compras.proveedore_id')
    // ->join('cotizaciones AS c', 'c.id', '=', 'ordenes_compras.cotizacione_id')
    ->join('estado_compras AS ec', 'ec.id', '=', 'ordenes_compras.estado_id')
    ->join('empleados AS ee', 'ee.id', '=', 'ordenes_compras.elabora_empleado_id')
    ->join('empleados AS ea', 'ea.id', '=', 'ordenes_compras.autoriza_empleado_id')
    ->join('condicion_pago AS CP','CP.id','=','ordenes_compras.condicion_pago_id')
    ->join('pagos_no_recurrentes AS PNR','PNR.ordenes_comp_id','=','ordenes_compras.id')
    ->join('proyectos AS pro','pro.id','=','ordenes_compras.proyecto_id')
    ->select('ordenes_compras.*','PNR.rango_dias','PNR.eventos AS pagos','p.nombre as pnom','p.razon_social as prz','CP.nombre AS nombre_condicion_pago',
    'pro.nombre as nombre_proyecto','pro.nombre_corto AS nombre_corto_proyecto',
    DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_empleado_elabora"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"))
    ->where('ordenes_compras.proyecto_id','=',$id)
    ->get();
    foreach ($compras as $key => $compra) {
      $arreglo[] = [
        'compra' => $compra,
      ];
    }
    return response()->json($arreglo);
  }

  /*
  * Función que recibe un parametro id y retorna un json de los impuestos que pertenecientes a la orden de compra del id que recibe
  */
  public function impuesto($id)
  {
    $impuestos = \App\Impuesto::where('orden_compra_id','=',$id)->get();
    return response()->json($impuestos);
  }

  /*
  * Función que recibe un parametro id y retorna un status true al eliminar impuestos que pertenecientes al del id que recibe
  */
  public function impuestoeliminar($id)
  {
    $impuestos = \App\Impuesto::where('id','=',$id)->first();
    $impuestos->delete();
    return response()->json(array('status' => true));
  }

  public function Listado(Request $request)
  {
    $listOrden = Compras::orderBy('id', 'desc')->get()->toArray();
    return response()->json($listOrden);
  }

  /*
  * Función que retorna un json de todas la condiciones de pago existentes
  */
  public function condicionpago()
  {
    $condicionpago = DB::table('condicion_pago')->select('condicion_pago.*')->get();
    return response()->json($condicionpago);
  }

  /**
  * [requisicionesporautorizar Busca en las requisiciones con estado_id = 2]
  * @return Response [description]
  */
  public function requisicionesrecibir()
  {

    // $id = Auth::id();
    // $users = DB::table('users')->select('users.*')->where('users.id','=',$id)->first();
    $requisiciones = DB::table('requisiciones')
    ->leftJoin('proyectos AS p', 'p.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
    ->select('requisiciones.*','p.nombre_corto AS nombrep',
    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombre_solicita"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_autoriza"))
    // ->where('autoriza_empleado_id','=',$users->empleado_id)
    ->where('requisiciones.estado_id','=','6')
    ->get();
    return response()->json($requisiciones);
  }

  public function porProyecto($data)
  {
    $valores = explode('&',$data);
    $id = $valores[0];
    $fecha_uno = $valores[1];
    $fecha_dos = $valores[2];

    $proyectos = DB::table('proyectos')
    ->select('id')
    ->where('id', $id)
    ->orWhere('proyecto_id', $id)
    ->get()->toArray();

    $proyectos_id = array_column($proyectos, 'id');

    $compras = DB::table('ordenes_compras AS c')
    ->select('c.id', 'c.folio', 'c.condicion_pago_id', 'c.fecha_orden', 'c.descuento', 'c.total', 'c.moneda',
    'c.tipo_cambio', 'c.proveedore_id', 'c.estado_id', 'c.condicion', 'e.nombre AS estado_compra',
    'p.nombre AS condicion_pago', 'v.nombre AS proveedor', 'y.nombre_corto AS proyecto','v.razon_social')
    ->join('estado_compras as e', 'c.estado_id', '=',  'e.id')
    ->join('condicion_pago as p', 'c.condicion_pago_id', '=', 'p.id')
    ->join('proveedores as v', 'c.proveedore_id', '=', 'v.id')
    ->join('proyectos AS y', 'c.proyecto_id', '=', 'y.id')
    ->whereIn('c.proyecto_id', $proyectos_id)
    ->whereBetween('fecha_orden',[$fecha_uno,$fecha_dos])
    ->get();

    return response()->json($compras);
  }

  public function partidasCompraProyecto($id)
  {
    $compras = DB::table('requisicion_has_ordencompras as c')
    ->select('c.id', 'c.cantidad', 'c.precio_unitario', 'a.nombre', 'a.descripcion', 'a.marca')
    ->join('articulos as a', 'c.articulo_id', '=',  'a.id')
    ->where('c.orden_compra_id', $id)
    ->get();

    return response()->json($compras);
  }

  public function serviciospendientes()
  {

    $partidas_compras = \App\requisicionhasordencompras::
    Join('ordenes_compras AS OC','OC.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->Join('requisiciones AS R','R.id','=','requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('servicios AS S','S.id','=','requisicion_has_ordencompras.servicio_id')
    ->leftJoin('proyectos AS P','P.id','=','OC.proyecto_id')
    ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','P.proyecto_subcategorias_id')
    ->join('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
    // ->join('usuario_categoria as uc','P.proyecto_subcategorias_id','=','uc.proyecto_subcategoria_id')
    // ->join('partidas_requisiciones', function ($join){
    //   $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
    //   ->on('requisicion_has_ordencompras.servicio_id','=','partidas_requisiciones.servicio_id');
    // })
    ->select('requisicion_has_ordencompras.*','S.nombre_servicio','S.proveedor_marca','S.unidad_medida',
    'P.nombre_corto AS nombre_proyeto',
    'OC.fecha_orden AS fecha_requerido',
    'OC.folio')
    ->where('requisicion_has_ordencompras.servicio_id','!=','null')
    ->where('OC.condicion','2')
    ->where('requisicion_has_ordencompras.condicion','=','1')
    // ->where(['uc.user_id' => Auth::user()->id])
    ->orderBy('OC.fecha_orden','DESC')
    ->get();

    return response()->json($partidas_compras);
  }

  public function terminarservicio(Request $request)
  {
    try {

      $partidas_compras = \App\requisicionhasordencompras::where('id','=',$request->id)->first();
      $partidas_compras->condicion = 0;
      $partidas_compras->save();

      $servicio_check = new \App\ServicioCheck();
      $servicio_check->fecha = date('Y-m-d');
      $servicio_check->rhoc_id = $partidas_compras->id;
      $servicio_check->save();

      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }

  }

  public function serviciosfinalizados($id)
  {
    $partidas_compras = \App\requisicionhasordencompras::leftJoin('ordenes_compras AS OC','OC.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->leftJoin('requisiciones AS R','R.id','=','requisicion_has_ordencompras.requisicione_id')
    ->leftJoin('servicios AS S','S.id','=','requisicion_has_ordencompras.servicio_id')
    ->leftJoin('proyectos AS P','P.id','=','OC.proyecto_id')
    ->join('partidas_requisiciones', function ($join){
      $join->on('requisicion_has_ordencompras.requisicione_id','=','partidas_requisiciones.requisicione_id')
      ->on('requisicion_has_ordencompras.servicio_id','=','partidas_requisiciones.servicio_id');
    })
    ->select('requisicion_has_ordencompras.*','S.nombre_servicio','S.proveedor_marca','S.unidad_medida','P.nombre AS nombre_proyeto','partidas_requisiciones.fecha_requerido')
    ->where('requisicion_has_ordencompras.servicio_id','!=','null')
    ->where('OC.estado_id','!=','3')
    ->where('requisicion_has_ordencompras.condicion','=','0')
    ->where('OC.proyecto_id','=',$id)
    ->get();
    return response()->json($partidas_compras);
  }

  public function compraseleccionarpartida($id)
  {
    $compras = DB::table('ordenes_compras')->select('ordenes_compras.*')->where('id','=',$id)->first();
    $partidas_costos = DB::table('partidas_costos')->select('partidas_costos.*')->where('proyecto_id','=',$compras->proyecto_id)->get();
    return response()->json($partidas_costos);
  }

  public function requisitadocomprado($id){
    $arreglo = [];
    $requisiciones_compras = DB::table('partidas_requisiciones')
    ->join('requisiciones AS R','R.id','=','partidas_requisiciones.requisicione_id')
    ->select('partidas_requisiciones.requisicione_id')->whereIn('partidas_requisiciones.condicion',[2,0])->where('R.proyecto_id',$id)
    ->distinct()->get();

    foreach ($requisiciones_compras as $key => $value) {
      $requisiciones = DB::table('requisiciones')
      ->join('proyectos AS p','p.id','=','requisiciones.proyecto_id')
      ->select('requisiciones.*','p.nombre_corto AS nombrep')->where('requisiciones.id','=',$value->requisicione_id)->first();
      $arreglo [] = [
        'requisicion' => $requisiciones,
      ];
    }
    return response()->json($arreglo);
  }

  public function compradoscancelados($id){
    $partidas_requisiciones_articulos = DB::table('partidas_requisiciones')
    ->join('articulos AS a','a.id','=','partidas_requisiciones.articulo_id')
    ->select('partidas_requisiciones.*',DB::raw("CONCAT(a.nombre,' ',a.descripcion) AS nombre"))
    ->whereIn('partidas_requisiciones.condicion',[2,0])
    ->where('partidas_requisiciones.requisicione_id',$id)
    ->where('partidas_requisiciones.articulo_id','!=','NULL')
    ->get()->toArray();
    $partidas_requisiciones_servicios = DB::table('partidas_requisiciones')
    ->join('servicios AS s','s.id','=','partidas_requisiciones.servicio_id')
    ->select('partidas_requisiciones.*',DB::raw("CONCAT(s.nombre_servicio,' ',partidas_requisiciones.comentario) AS nombre"))
    ->whereIn('partidas_requisiciones.condicion',[2,0])
    ->where('partidas_requisiciones.requisicione_id',$id)
    ->where('partidas_requisiciones.servicio_id','!=','NULL')
    ->get()->toArray();

    $partidas_requisiciones = array_merge($partidas_requisiciones_articulos,$partidas_requisiciones_servicios);
    return response()->json($partidas_requisiciones);
  }

  public function porProveedor(Request $request, $id)
  {
    $ordenes_compras = DB::table('ordenes_compras')
    ->join('proveedores', 'proveedores.id', '=', 'ordenes_compras.proveedore_id')
    ->select("ordenes_compras.*")
    ->where('ordenes_compras.proveedore_id', '=' , $id)

    ->get();
    return response()->json(
      $ordenes_compras->toArray()
    );
  }

  /**
  * [Query del lado del servidor de el modelo Articulo]
  * @return Array [Array que contiene data y count]
  */
  public function busqueda($id)
  {
    extract(request()->only(['query', 'limit', 'page', 'orderBy', 'ascending', 'byColumn']));

    $data = $this->getDataC($id);
    // Compras::select(
    //   [
    //     'id','folio','ordenes_formato','condicion_pago_id','periodo_entrega','fecha_orden','lugar_entrega','observaciones','descuento','total','moneda','tipo_cambio','referencia','cie','sucursal',
    //     'proyecto_id','proveedore_id','estado_id','elabora_empleado_id','autoriza_empleado_id','condicion','comentario_condicion_pago','conrequisicion','fecha_probable_pago','prioridad'
    //     ])->where('proyecto_id',$id)->orderBy('folio', 'ASC');
    // $data->orderBy('folio', 'ASC');

    if (isset($query) && $query) {
      $data = $byColumn == 1 ?
      $this->busqueda_filterByColumn($data, $query) :
      $this->busqueda_filter($data, $query, $fields);
    }

    $count = $data->count();

    $data->limit($limit)
    ->skip($limit * ($page - 1));

    // if (isset($orderBy)) {
    //
    //   $direction = $ascending == 1 ? 'ASC' : 'DESC';
    //   $data->orderBy($orderBy, $direction);
    // }


    // leftJoin('tipo_calidad AS TC','TC.id','=','articulos.calidad_id')
    // ->
    $results = $this->Joins($data->get());
    //$results = $data->get();
    return [
      'data' => $results,
      'count' => $count,
      // 'orderby' => $d,
    ];
  }
  /**
  * [Consulta en la BD el nombre y descripcion del tipo de calidad]
  * @param Array  $data [Array recibido en la función]
  * @param String $arreglo_final  [Cadena concatenada]
  */

  public function getDataC($id)
  {
    $compras = Compras::
    leftJoin('pagos_no_recurrentes AS pnr','pnr.ordenes_comp_id','=','ordenes_compras.id')
    ->leftJoin('proveedores AS p','p.id','=','ordenes_compras.proveedore_id')
    ->leftJoin('proveedores AS pcsct','pcsct.id','=','ordenes_compras.proveedore_csct_id')
    ->leftJoin('condicion_pago AS cp','cp.id','=','ordenes_compras.condicion_pago_id')
    ->leftJoin('proyectos AS pro','pro.id','=','ordenes_compras.proyecto_id')
    ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','pro.proyecto_subcategorias_id')
    ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
    ->leftJoin('empleados AS ee','ee.id','=','ordenes_compras.elabora_empleado_id')
    ->leftJoin('empleados AS ea','ea.id','=','ordenes_compras.autoriza_empleado_id')
    ->select('ordenes_compras.*','pnr.rango_dias','pnr.eventos','p.nombre AS proveedor_nombre',
    'p.razon_social AS proveedor_razon_social','cp.nombre AS nombre_condicion_pago','pro.nombre AS nombre_proyeto',
    'pc.nombre as nombre_categoria','pro.nombre_corto as nombre_corto_proyecto','pcsct.razon_social AS proveedor_razon_social_csct',
    DB::raw("CONCAT(ee.nombre,' ',ee.ap_paterno,' ',ee.ap_materno) AS nombre_empleado_elabora"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombre_empleado_autoriza"))
    ->where('ordenes_compras.proyecto_id',$id)->orderBy('ordenes_compras.folio', 'DESC');

    return $compras;
  }

  public function Joins($data)
  {
    $arreglo_final = [];
    foreach ($data as $key => $value) {
      $pdboc = PartidasDatosBancariosOC::where('orden_compra_id',$value->id)->first();
      $pago_no_recurrentes = \App\PagosNoRecurrentes::where('ordenes_comp_id','=',$value->id)->first();
      $proveedores = \App\Proveedor::where('id',$value->proveedore_id)->first();
      $condicion_pago = \App\CondicionPago::where('id',$value->condicion_pago_id)->first();
      $proyecto = \App\Proyecto::
      leftJoin('proyecto_subcategorias AS PS','PS.id','=','proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS PC','PC.id','=','PS.proyecto_categoria_id')
      ->select('proyectos.*','PC.nombre AS nombre_categoria')
      ->where('proyectos.id',$value->proyecto_id)->first();
      $empleado_elabora = \App\Empleado::select(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_empleado_elabora"))->where('id',$value->elabora_empleado_id)->first();
      $empleado_autoriza = \App\Empleado::select(DB::raw("CONCAT(nombre,' ',ap_paterno,' ',ap_materno) AS nombre_empleado_autoriza"))->where('id',$value->autoriza_empleado_id)->first();

      $arreglo_final [] = array_merge($value->toArray(), [
        'rango_dias' =>  isset($pago_no_recurrentes) == false ? 0 : $pago_no_recurrentes->rango_dias ,
        'pagos' => isset($pago_no_recurrentes) == false ? 0 : $pago_no_recurrentes->eventos,
        'proveedor_nombre' => $proveedores->nombre,
        'proveedor_razon_social' => $proveedores->razon_social,
        'nombre_condicion_pago' => isset($condicion_pago) == false ? '' : $condicion_pago->nombre,
        'nombre_proyeto' => $proyecto->nombre,
        'nombre_categoria' => $proyecto->nombre_categoria,
        'nombre_corto_proyecto' => $proyecto->nombre_corto,
        'nombre_empleado_elabora' => isset($empleado_elabora) == true ? $empleado_elabora->nombre_empleado_elabora : 0,
        'nombre_empleado_autoriza' => isset($empleado_autoriza) == true ? $empleado_autoriza->nombre_empleado_autoriza : 0,
        'datos_bancarios' => $pdboc,
      ]);
    }
    return $arreglo_final;
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

  public function getAsignaciones($id)
  {
    $costosProyectosAdmin = CostosProyectosAdmin::join('proyectos AS P','P.id','=','costos_proyectos_administrativos.proyecto_id')
    ->join('catalogo_centro_costos AS CCC','CCC.id','=','costos_proyectos_administrativos.catalogo_centro_costos_id')
    ->leftJoin('catalogo_centro_costos AS CCCS','CCCS.id','=','CCC.catalogo_centro_costos_id')
    ->select('costos_proyectos_administrativos.*','P.nombre_corto','CCC.nombre AS nombre_cat','CCCS.nombre AS nombre_sub')
    ->where('costos_proyectos_administrativos.compra_id','=',$id)->get();

    return response()->json($costosProyectosAdmin);
  }

  public function deleteAsignacion($id)
  {
    try {
      $costosProyectosAdmin = CostosProyectosAdmin::where('id',$id)->delete();
      return response()->json(array('status' => true));
    }catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }
  }

  public function guardarpartidacosto(Request $request)
  {
    try {
      $busqueda = CostosProyectosServicios::where('requisicion_has_ordencompra_id',$request->id_partida)->first();
      if(isset($busqueda) == true){
        $busqueda->catalogo_centro_costos_id = $request->centro_costo_id;
        $busqueda->save();
      }else {
        $partidas_costos = new CostosProyectosServicios();
        $partidas_costos->catalogo_centro_costos_id = $request->centro_costo_id;
        $partidas_costos->requisicion_has_ordencompra_id = $request->id_partida;
        Utilidades::auditar($partidas_costos, $partidas_costos->id);
        $partidas_costos->save();
      }
      return response(array('status' => true));
    }catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  public function actualizarfp(Request $request)
  {
    try {
      $compras = Compras::where('id',$request->compra_id)->first();
      $compras->fecha_probable_pago = $request->fecha_probable_pago;
      $compras->prioridad = $request->prioridad;
      Utilidades::auditar($compras, $compras->id);
      $compras->save();
      return response()->json(array('status' => true));
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return response()->json(array('status' => 'error'));
    }

  }

  public function Excel($id)
  {
    return Excel::downlocad(new ComprasExport($id), 'ReporteCompras.xlsx' );

  }

  public function ExcelTrazabilidad($id)
  {
    $ordenes_compras = DB::table('ordenes_compras AS oc')
    ->join('proveedores AS p','p.id','oc.proveedore_id')
    ->join('proyectos AS pr','pr.id','oc.proyecto_id')
    ->select('oc.folio','oc.fecha_orden','oc.condicion_pago_id','oc.periodo_entrega','oc.comentario_condicion_pago','oc.moneda',
    'p.razon_social','pr.nombre_corto')
    ->where('oc.id','!=','1')->get();

    $arreglo = [];
    // foreach ($ordenes_compras as $key => $value) {
    //   $requisicionhasordencompras  = DB::table
    // }

    return response()->json($ordenes_compras);

    //return Excel::download(new ComprasTrazabilidadExport($id), 'Estado_Materiales.xlsx' );
  }



  public function ExcelTrazabilidadGeneral($id)
  {
    return Excel::download(new ComprasTrazabilidadGeneralExport($id), 'Estado_Materiales.xlsx' );
  }

  public function trazabilidad($id)
  {
    $arreglo = [];
    $valores = explode('&',$id);
    if ($valores[0] === 'Proyecto') {
      $where = 'ordenes_compras.proyecto_id';
    }elseif ($valores[0] === 'Proveedor') {
      $where = 'ordenes_compras.proveedore_id';
    }

    $ordencompra = Compras::
    join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
    ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
    'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
    ->whereIn($where,$valores)
    ->get();

    foreach ($ordencompra as $key => $value) {
      $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
      ->where('orden_compra_id',$value->id)->first();
      $total_sin_en = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
      ->where('orden_compra_id',$value->id)
      ->where('articulo_id','!=','NULL')
      ->where('condicion','1')->first();
      $total_con_en = requisicionhasordencompras::
      // join('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
      // ->join('lotes AS l','l.entrada_id','=','pe.id')
      // ->join('lote_almacen AS la','la.lote_id','=','l.id')
      // ->join('lote_temporal AS lt','lt.lote_almacen_id','=','la.id')
      select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
      // ->select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad) + SUM(lt.cantidad)) AS suma_total_con_en'))
      ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      // ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
      ->where('requisicion_has_ordencompras.condicion','0')->first();


      // $total_temp = LoteTemporal::
      // Join('lote_almacen AS la','la.id','=','lote_temporal.lote_almacen_id')
      // ->Join('lotes AS l','l.id','=','la.lote_id')
      // ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
      // ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
      // ->where('RHOC.orden_compra_id',$value->id)
      // ->select(DB::raw('SUM(lote_temporal.cantidad) AS suma_total_partidas_temp'))
      // ->first();


      // foreach ($total_oc as $key => $valor) {
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

      $total_salidas = 0;
      $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
      if ($total_con_en_ser->suma_total_con_en_ser != null) {
        $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
      }
      // }
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
      ->where('entrada_id','0')->get();

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


      $arreglo [] = [
        'oc' => $value,
        'total_par' => $total,
        'total_sin_entrada' => $total_sin_entradas,
        'total_con_entrada' => $total_con_entradas,
        'procentaje_entrada' => $porcentaje,
        'total_salidas' => $total_salidas,
        'porcentaje_salidas' => $porcentaje_salida,
        'total_factura' => $total_factura,
        'factura' => $facturas_folios,
        'diferencia_costos' => $diferencia_costos,
        'pago_mn' => $total_pagos,
        'tipo_cambio' => $tipo_cambio,
        'porcentaje_pago' => $porcentaje_pago,
      ];
    }

    return response()->json($arreglo);
  }

  ////historico de compras
  public function historico($id)
  {
    $arreglo = [];
    $valores = explode('&',$id);
    if ($valores[0] === 'Proyecto') {
      $where = 'ordenes_compras.proyecto_id';
    }elseif ($valores[0] === 'Proveedor') {
      $where = 'ordenes_compras.proveedore_id';
    }

    $ordencompra = Compras::
    join('proyectos AS p','p.id','=','ordenes_compras.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','ordenes_compras.proveedore_id')
    ->select('ordenes_compras.id AS id','ordenes_compras.fecha_orden','ordenes_compras.folio','ordenes_compras.total',
    'ordenes_compras.moneda','ordenes_compras.proyecto_id','p.nombre_corto','pr.razon_social')
    ->whereIn($where,$valores)
    ->get();

    foreach ($ordencompra as $key => $value) {
      $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
      ->where('orden_compra_id',$value->id)->first();
      $total_sin_en = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_sin_en'))
      ->where('orden_compra_id',$value->id)
      ->where('articulo_id','!=','NULL')
      ->where('condicion','1')->first();
      $total_con_en = requisicionhasordencompras::
      // join('partidas_entradas AS pe','pe.req_com_id','=','requisicion_has_ordencompras.id')
      // ->join('lotes AS l','l.entrada_id','=','pe.id')
      // ->join('lote_almacen AS la','la.lote_id','=','l.id')
      // ->join('lote_temporal AS lt','lt.lote_almacen_id','=','la.id')
      select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
      // ->select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad) + SUM(lt.cantidad)) AS suma_total_con_en'))
      ->where('requisicion_has_ordencompras.orden_compra_id',$value->id)
      // ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
      ->where('requisicion_has_ordencompras.condicion','0')->first();


      // $total_temp = LoteTemporal::
      // Join('lote_almacen AS la','la.id','=','lote_temporal.lote_almacen_id')
      // ->Join('lotes AS l','l.id','=','la.lote_id')
      // ->Join('partidas_entradas AS pa','pa.id','=','l.entrada_id')
      // ->Join('requisicion_has_ordencompras AS RHOC','RHOC.id','=','pa.req_com_id')
      // ->where('RHOC.orden_compra_id',$value->id)
      // ->select(DB::raw('SUM(lote_temporal.cantidad) AS suma_total_partidas_temp'))
      // ->first();


      // foreach ($total_oc as $key => $valor) {
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

      $total_salidas = 0;
      $total_salidas = $partidas->suma_total_partidas == null ? 0 :$partidas->suma_total_partidas;
      if ($total_con_en_ser->suma_total_con_en_ser != null) {
        $total_salidas += (float)$total_con_en_ser->suma_total_con_en_ser;
      }
      // }
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
      ->where('entrada_id','0')->get();

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


      $arreglo [] = [
        'oc' => $value,
        'total_par' => $total,
        'total_sin_entrada' => $total_sin_entradas,
        'total_con_entrada' => $total_con_entradas,
        'procentaje_entrada' => $porcentaje,
        'total_salidas' => $total_salidas,
        'porcentaje_salidas' => $porcentaje_salida,
        'total_factura' => $total_factura,
        'factura' => $facturas_folios,
        'diferencia_costos' => $diferencia_costos,
        'pago_mn' => $total_pagos,
        'tipo_cambio' => $tipo_cambio,
        'porcentaje_pago' => $porcentaje_pago,
      ];
    }

    return response()->json($arreglo);
  }

  public function HistoricoPdf($id)
  {
    $arreglo = [];
    $valores = explode('&',$id);
    if ($valores[0] === 'Proyecto') {
      $where = 'oc.proyecto_id';
    }elseif ($valores[0] === 'Proveedor') {
      $where = 'oc.proveedore_id';
    }

    $ordencompra_art = requisicionhasordencompras::
    join('articulos AS a','a.id','requisicion_has_ordencompras.articulo_id')
    ->join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
    ->join('proyectos AS p','p.id','=','oc.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','oc.proveedore_id')
    ->select('requisicion_has_ordencompras.id AS req_com_id','oc.fecha_orden',
    'oc.folio','requisicion_has_ordencompras.precio_unitario',
    'oc.total AS c_oc',
    'requisicion_has_ordencompras.cantidad','a.descripcion AS nombre',
    'oc.moneda','oc.proyecto_id','p.nombre_corto','pr.razon_social')
    ->where('requisicion_has_ordencompras.articulo_id','!=','NULL')
    ->whereIn($where,$valores)
    ->get()->toArray();

    $ordencompra_serv = requisicionhasordencompras::
    join('servicios AS s','s.id','requisicion_has_ordencompras.servicio_id')
    ->join('ordenes_compras AS oc','oc.id','requisicion_has_ordencompras.orden_compra_id')
    ->join('proyectos AS p','p.id','=','oc.proyecto_id')
    ->join('proveedores AS pr','pr.id','=','oc.proveedore_id')
    ->select('requisicion_has_ordencompras.id AS req_com_id','oc.fecha_orden','oc.folio',
    'requisicion_has_ordencompras.precio_unitario',
    'oc.total AS c_oc',
    'requisicion_has_ordencompras.cantidad','s.nombre_servicio AS nombre',
    'oc.moneda','oc.proyecto_id','p.nombre_corto','pr.razon_social')
    ->where('requisicion_has_ordencompras.servicio_id','!=','NULL')
    ->whereIn($where,$valores)
    ->get()->toArray();

    $orden_compra = array_merge($ordencompra_art,$ordencompra_serv);
    // return response()->json($orden_compra);

    $arreglo = [];
    foreach ($orden_compra as $key => $value) {

      $entradas = \App\PartidaEntrada::
      leftJoin('lotes AS l','l.entrada_id','partidas_entradas.id')
      ->leftJoin('lote_almacen AS la','la.lote_id','l.id')
      ->leftJoin('movimientos AS m','m.lote_id','la.id')
      ->select('partidas_entradas.*','m.fecha','l.nombre AS lote_nombre')
      ->where('m.tipo_movimiento','Entrada')
      ->where('partidas_entradas.req_com_id',$value['req_com_id'])
      ->get();

      $salidas = \App\PartidaEntrada::
      leftJoin('lotes AS l','l.entrada_id','partidas_entradas.id')
      ->leftJoin('lote_almacen AS la','la.lote_id','l.id')
      ->leftJoin('movimientos AS m','m.lote_id','la.id')
      ->select('partidas_entradas.*','m.fecha','l.nombre AS lote_nombre')
      ->where('m.tipo_movimiento','Salida')
      ->where('partidas_entradas.req_com_id',$value['req_com_id'])
      ->get();

    $arreglo [] = [
      'oc' => $value,
      'entradas' => $entradas,
      'salidas' => $salidas
    ];
    }

    // return response()->json($arreglo);

    $pdf = PDF::loadView('pdf.historico', compact('arreglo'));
    //  $pdf->setPaper('A4', 'landscape');
    $pdf->setPaper("ledger", "portrait");
    return $pdf->stream();

  }

////////////////////////

  public function comprastrazabilidad($id)
  {
    $valores = explode('&',$id);
    $arreglo = [];
    $compras = Compras::where('proyecto_id',$valores[0])->get();
    $total_usd_oc = 0; $total_mnx_oc = 0;
    foreach ($compras as $key => $compra) {
      if ($compra->moneda == 1) {
        $total_usd_oc += (floatval(str_replace(',','',$compra->total)));
      }elseif ($compra->moneda == 2) {
        $total_mnx_oc += (floatval(str_replace(',','',$compra->total)));
      }

    }
    $total_cotizacion = 0;
    $cotizado = DB::table('partidas_costos')
    ->where('proyecto_id',$valores[0])->get();

    $totales_cotizaciones = 0;
    $total_usd_cot = 0;$total_mnx_cot = 0;

    foreach ($cotizado as $kc => $partida) {
      if ($partida->moneda == 1) {
        $total_mnx_cot += (floatval($partida->monto_cotizado));
      }elseif ($partida->moneda == 2) {
        $total_usd_cot += (floatval($partida->monto_cotizado)) * floatval($valores[1]);
      }
    }
    $totales_cotizaciones = $total_mnx_cot +  $total_usd_cot;

    $pagos_clientes = DB::table('pagos_cliente')->select(DB::raw("SUM(abonos) AS abonos"))->where('proyecto_id',$valores[0])->first();

    $facturas = FacturasEntradas::Join('ordenes_compras AS oc','oc.id','=','facturas_entradas.orden_compra_id')
    ->where('oc.proyecto_id',$valores[0])->where('facturas_entradas.total_factura','!=','')->get();
    $total_usd_f = 0;$total_mnx_f = 0;
    foreach ($facturas as $key => $factura) {
      if ($factura->moneda == 1) {
        $total_usd_f += (floatval(str_replace(',','',$factura->total_factura)));
      }elseif ($factura->moneda == 2) {
        $total_mnx_f += (floatval(str_replace(',','',$factura->total_factura)));
      }
    }
    $proyecto = DB::table('proyectos')->where('id',$valores[0])->first();
    // $pagos = PagoCompra::join('pagos_no_recurrentes AS pnr','pnr.id','=','pagos_compras.pagos_no_recurrentes_id')
    // ->join('ordenes_compras AS oc','oc.id','=','pnr.ordenes_comp_id')
    // ->select('pagos_compras.*')
    // ->where('oc.proyecto_id',$id)->get();
    $pagos = PagoCompra::where('proyecto','LIKE',$proyecto->nombre_corto)->get();

    $total_usd_p = 0;$total_mnx_p = 0;
    foreach ($pagos as $key => $pago) {
      if ($pago->tipo_cambio == 1) {
        $total_usd_p += (floatval(str_replace(',','',$pago->cargo)));
      }elseif ($pago->tipo_cambio == 2) {
        $total_mnx_p += (floatval(str_replace(',','',$pago->cargo)));
      }
    }

    $arreglo [] = [
      'total_mnx_oc' => $total_mnx_oc,
      'total_usd_oc' => $total_usd_oc,
      'total_mnx_f' => $total_mnx_f,
      'total_usd_f' => $total_usd_f,
      'total_mnx_p' => $total_mnx_p,
      'total_usd_p' => $total_usd_p,
      'cotizacion' => $totales_cotizaciones,
      'pagos_cliente' => (float) $pagos_clientes->abonos,
    ];
    return response()->json($arreglo);
  }

  public function comprastrazabilidadproveedores($id)
  {
    $valores = explode('&',$id);
    $proveedores_oc = DB::table('proveedores AS p')
    ->join('ordenes_compras AS oc','oc.proveedore_csct_id','=','p.id')
    ->select('p.id')->where('oc.proyecto_id',$valores[0])
    ->groupBy('p.id')->get();

    // return response()->json($proveedores_oc);
    $arreglo_pro_pro = [];
    foreach ($proveedores_oc as $ks => $vs) {

      $oc_m = DB::table('ordenes_compras AS oc')
      ->select(DB::raw("SUM(oc.total_aux) as total_pro"))
      ->where('oc.proveedore_csct_id',$vs->id)
      ->where('oc.proyecto_id',$valores[0])
      ->where('oc.moneda','2')
      ->first();

      $oc_u = DB::table('ordenes_compras AS oc')
      ->select(DB::raw("SUM(oc.total_aux) as total_pro"))
      ->where('oc.proveedore_csct_id',$vs->id)
      ->where('oc.proyecto_id',$valores[0])
      ->where('oc.moneda','1')
      ->first();


      $proveedor = DB::table('proveedores AS p')->where('id',$vs->id)->first();

      $ordenes_compras = DB::table('ordenes_compras AS oc')
      ->where('oc.proveedore_csct_id',$vs->id)
      ->where('oc.proyecto_id',$valores[0])
      ->get();

      $arreglo_uno = [];
      foreach ($ordenes_compras as $key_ => $value_) {
        $partidas_art = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('articulos AS a','a.id','rhoc.articulo_id')
        ->select('rhoc.*','a.descripcion')
        ->where('rhoc.orden_compra_id',$value_->id)
        ->where('rhoc.articulo_id','!=','NULL')
        ->get()->toArray();

        $partidas_serv = DB::table('requisicion_has_ordencompras AS rhoc')
        ->join('servicios AS s','s.id','rhoc.servicio_id')
        ->select('rhoc.*','s.nombre_servicio AS descripcion')
        ->where('rhoc.orden_compra_id',$value_->id)
        ->where('rhoc.servicio_id','!=','NULL')
        ->get()->toArray();

        $total_oc = requisicionhasordencompras::select(DB::raw('SUM(cantidad) AS suma_total_oc'))
        ->where('orden_compra_id',$value_->id)->first();

        $total_con_en = requisicionhasordencompras::
        select(DB::raw('(SUM(requisicion_has_ordencompras.cantidad)) AS suma_total_con_en'))
        ->where('requisicion_has_ordencompras.orden_compra_id',$value_->id)
        ->where('requisicion_has_ordencompras.condicion','0')->first();

        $total = $total_oc->suma_total_oc == null ? 0 : $total_oc->suma_total_oc;
        $total_con_entradas = $total_con_en->suma_total_con_en == null ? 0 : $total_con_en->suma_total_con_en;
        $calculo= '';
        if (($total_con_entradas) == 0) {
        $calculo = $this->calculo_dia_entrega($value_);
        $porcentaje = 0;
        }else {
        $porcentaje = (($total_con_entradas) * 100) / ($total);
        }

        $partidas = array_merge($partidas_art,$partidas_serv);

        $arreglo_uno [] = [
          'oc' => $value_,
          'porcentaje' => $porcentaje,
          'calculo' => $calculo,
          'p' => $partidas,
        ];
      }

      $arreglo_pro_pro [] = [
        'proveedor_id' => $proveedor->id,
        'proveedor' => $proveedor->razon_social,
        'total_mnx_parcial' => $oc_m->total_pro == 0 ? '' : '$ '.number_format($oc_m->total_pro, 2),
        'total_usd_parcial' => $oc_u->total_pro == 0 ? '' : '$ '.number_format(($oc_u->total_pro),2),
        'total_mnx' => '$ '.number_format($oc_m->total_pro + ($oc_u->total_pro * $valores[1]),2),
        'total_mnx_s' => '$ '.number_format( ($oc_m->total_pro + ($oc_u->total_pro * $valores[1]))/1.16 ,2),
        'total' => $oc_m->total_pro + ($oc_u->total_pro * $valores[1]),
        // 'ocs' => $ordenes_compras,
        'paridas' => $arreglo_uno,
      ];
    }
    foreach ($arreglo_pro_pro as $ka => $a) {
      $orden_uno[$ka] = $a['total'];
    }
    array_multisort($orden_uno,SORT_DESC,$arreglo_pro_pro);
    return response()->json($arreglo_pro_pro);

  }

  public function calculo_dia_entrega($data)
  {
    try {
      $fecha_orden = $data->fecha_orden;
      $periodo_entrega = $data->periodo_entrega;
      $valores = explode(' ',$periodo_entrega);
      $dias = 0;

      if (count($valores) == 2) {
        if ($valores[1] === 'día' || $valores[1] === 'días' || $valores[1] === 'dias' || $valores[1] === 'dia' || $valores[1] === 'DIAS' || $valores[1] === 'DIA') {
          $dias = $valores[0];
        }elseif ($valores[1] === 'semana' || $valores[1] === 'semanas' || $valores[1] === 'SEMANAS' || $valores[1] === 'SEMANA') {
          $dias = $valores[0] * 7;
        }
      }

      $fecha = date("Y-m-d",strtotime($fecha_orden."+ ".$dias." days"));

      return $fecha;
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function buscarproveedor($id)
  {
    $compras = Compras::where('proveedore_id',$id)->orderBy('folio', 'asc')->get();
    $arreglo = [];
    foreach ($compras as $key => $value) {
      $facturas = DB::table('facturas_entradas')->where('orden_compra_id',$value->id)->where('total_factura','!=','')->get();

      $pagos = DB::table('pagos_no_recurrentes')
      ->Join('pagos_compras AS pc','pc.pagos_no_recurrentes_id','=','pagos_no_recurrentes.id')
      ->select('pc.*')
      ->where('pagos_no_recurrentes.ordenes_comp_id',$value->id)
      ->get();

      $arreglo [] = [
        'oc' => $value,
        'facturas' => $facturas,
        'pagos' => $pagos,
      ];
    }
    return response()->json($arreglo);
  }

  public function busquedaestadoproveedor($id)
  {
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
  }

  public function descargarEstadoPro($id)
  {
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
    $tc = 0;$tp = 0;
    foreach ($final as $key => $value) {
      if ($value->tipo === 'compra') {
        $total -= (floatval(str_replace(',','',$value->total)));
        $tc += (floatval(str_replace(',','',$value->total)));
      }elseif ($value->tipo === 'pago') {
        $total += (floatval(str_replace(',','',$value->total)));
        $tp += (floatval(str_replace(',','',$value->total)));
      }

      $arreglo [] = ['ocp' => $value,'saldo' => $total];
    }

    $proveedor = DB::table('proveedores')->where('id',$id)->first();

    $pdf = PDF::loadView('pdf.proveedorestado', compact('arreglo','tc','tp','proveedor'));
    //  $pdf->setPaper('A4', 'landscape');
    $pdf->setPaper("A4", "portrait");
    // return $pdf->download('cv-interno.pdf');
    return $pdf->stream();

  }

  public function excelTotales($id)
  {
    return Excel::download(new ComprasTrazabilidadTotalesExport($id), 'TotalesCompras.xlsx' );
  }

  public function buscarocpf($id)
  {
    $compras = Compras::where('proyecto_id',$id)->orderBy('folio', 'asc')->get();
    $arreglo = [];
    foreach ($compras as $key => $value) {
      $facturas = DB::table('facturas_entradas')->where('orden_compra_id',$value->id)->where('total_factura','!=','')->get();

      $pagos = DB::table('pagos_no_recurrentes')
      ->Join('pagos_compras AS pc','pc.pagos_no_recurrentes_id','=','pagos_no_recurrentes.id')
      ->select('pc.*')
      ->where('pagos_no_recurrentes.ordenes_comp_id',$value->id)
      ->get();

      $arreglo [] = [
        'oc' => $value,
        'facturas' => $facturas,
        'pagos' => $pagos,
      ];
    }
    return response()->json($arreglo);
  }

  public function trazabilidadcostos($id)
  {
    $arreglo = [];
    $compras_usd = Compras::where('proyecto_id',$id)
    ->where('moneda','1')->get();

    $compras_mnx = Compras::where('proyecto_id',$id)
    ->where('moneda','2')->get();

    $total_usd = 0;
    foreach ($compras_usd as $key => $usd) {
      $total_usd += floatval(str_replace(',','',$usd->total));
    }

    $total_mnx = 0;
    foreach ($compras_mnx as $key => $mnx) {
      $total_mnx += floatval(str_replace(',','',$mnx->total));
    }

    $arreglo = ['usd' => $total_usd,'mnx' =>$total_mnx ];
    return response()->json($arreglo);

  }

  public function compraArticulo(Request $request)
  {

    $acfw = requisicionhasordencompras::leftJoin('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
    ->join('ordenes_compras AS oc','oc.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->join('proveedores AS p','p.id','oc.proveedore_id')
    ->where('a.nombre','LIKE','%'.$request->articulo.'%')
    ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
    ->orWhere('requisicion_has_ordencompras.comentario','LIKE','%'.$request->articulo.'%')
    ->select('oc.id AS ids','oc.fecha_orden AS fecha_orden','p.razon_social','a.nombre AS nombre','a.descripcion AS desc','a.unidad as unidad',
    'requisicion_has_ordencompras.comentario AS comentario','oc.folio AS folio','requisicion_has_ordencompras.id AS rhc_id',
    'requisicion_has_ordencompras.cantidad AS cantidad','requisicion_has_ordencompras.precio_unitario','oc.moneda')
    ->get()->toArray();

    $accsct = requisicionhasordencomprasDBP::leftJoin('articulos AS a','a.id','=','requisicion_has_ordencompras.articulo_id')
    ->join('ordenes_compras AS oc','oc.id','=','requisicion_has_ordencompras.orden_compra_id')
    ->join('proveedores AS p','p.id','oc.proveedore_id')
    ->where('a.nombre','LIKE','%'.$request->articulo.'%')
    ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
    ->orWhere('requisicion_has_ordencompras.comentario','LIKE','%'.$request->articulo.'%')
    ->select('oc.id AS ids','oc.fecha_orden AS fecha_orden','p.razon_social','a.nombre AS nombre','a.descripcion AS desc','a.unidad as unidad',
    'requisicion_has_ordencompras.comentario AS comentario','oc.folio AS folio','requisicion_has_ordencompras.id AS rhc_id',
    'requisicion_has_ordencompras.cantidad AS cantidad','requisicion_has_ordencompras.precio_unitario','oc.moneda')
    ->get()->toArray();

    $compras = array_merge($acfw,$accsct);

    return response()->json($compras);
  }

  public function LoteArticulo(Request $request)
  {
    $la = DB::table('lote_almacen AS la')
    ->join('articulos AS a','a.id','la.articulo_id')
    ->join('lotes AS l','l.id','la.lote_id')
    ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
    ->join('requisicion_has_ordencompras As rhoc','rhoc.id','pe.req_com_id')
    ->join('ordenes_compras AS oc','oc.id','=','rhoc.orden_compra_id')
    ->where('la.cantidad','>',0)
    ->where('a.nombre','LIKE','%'.$request->articulo.'%')
    ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
    ->orWhere('rhoc.comentario','LIKE','%'.$request->articulo.'%')
    ->select('oc.id AS ids','oc.fecha_orden AS fecha_orden','a.nombre AS nombre','a.descripcion AS desc','a.unidad as unidad',
    'rhoc.comentario AS comentario','oc.folio AS folio','rhoc.id AS rhc_id','la.cantidad',
    'rhoc.precio_unitario','la.id AS lote_id','a.id AS articulo_id','rhoc.requisicione_id')
    ->get();

    // if ($request->tipo == 1) {
    //   // code...
    //   $la = DB::select("SELECT oc.id AS ids,oc.fecha_orden AS fecha_orden,a.nombre AS nombre, a.descripcion AS descr ,a.unidad AS unidad,rhoc.comentario AS comentario,oc.folio AS folio,rhoc.id AS rhc_id,la.cantidad,rhoc.precio_unitario,la.id AS lote_id,l.id AS lotes_id,a.id AS articulo_id,rhoc.requisicione_id FROM lote_almacen AS la join articulos AS a on a.id = la.articulo_id join lotes AS l on l.id = la.lote_id join partidas_entradas AS pe on pe.id = l.entrada_id join requisicion_has_ordencompras AS rhoc on rhoc.id = pe.req_com_id join ordenes_compras AS oc on oc.id = rhoc.orden_compra_id where la.cantidad > 0 AND (a.nombre LIKE '%$request->articulo%' OR a.descripcion LIKE '%$request->articulo%' OR rhoc.comentario LIKE '%$request->articulo%')");
    // }elseif ($request->tipo == 2) {
    //   $la = DB::select("SELECT oc.id AS ids,oc.fecha_orden AS fecha_orden,a.nombre AS nombre, a.descripcion AS descr ,a.unidad AS unidad,rhoc.comentario AS comentario,oc.folio AS folio,rhoc.id AS rhc_id,la.cantidad,rhoc.precio_unitario,la.id AS lote_id,l.id AS lotes_id,a.id AS articulo_id,rhoc.requisicione_id FROM lote_almacen AS la join articulos AS a on a.id = la.articulo_id join lotes AS l on l.id = la.lote_id join partidas_entradas AS pe on pe.id = l.entrada_id join requisicion_has_ordencompras AS rhoc on rhoc.id = pe.req_com_id join ordenes_compras AS oc on oc.id = rhoc.orden_compra_id join proyectos AS p on p.id = oc.proyecto_id where la.cantidad > 0 AND p.id = '$request->proyecto' ");
    // }


    return response()->json($la);
  }

  public function requiArticulo(Request $request)
  {
    $ar = PartidaRe::leftJoin('articulos AS a','a.id','=','partidas_requisiciones.articulo_id')
    ->Join('requisiciones AS r','r.id','=','partidas_requisiciones.requisicione_id')
    ->Join('proyectos AS p','p.id','=','r.proyecto_id')
    ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
    ->join('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
    ->whereIn('p.nombre',['Proyectos','Servicios'])
    ->where('a.nombre','LIKE','%'.$request->articulo.'%')
    ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
    ->where('r.estado_id','!=','11')
    ->select('r.id as ids','r.folio as folio','a.nombre AS nombre','a.descripcion AS desc','a.unidad as unidad')
    ->get();

    return response()->json($ar);
  }

  public function salidasArticulo(Request $request)
    {
      // Salidas taller
      $st = Partidas::
      join('salidas AS s','s.id','partidas.salida_id')
      ->join('empleados AS e','e.id','s.empleado_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->Join('proyectos AS p2','p2.id','=','s.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->whereIn('p.nombre',['Proyectos','Servicios'])
      ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','1')
      ->select(
      'partidas.cantidad AS cantidad_salida',
      's.id as ids',
      DB::raw("('Taller') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      "p2.nombre_corto as p_salida",
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      's.empleado_id',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
      'oc.folio AS oc_folio')
      ->get()->toArray();

      // Salidas sitio
      $ss = Partidas::
      join('salidassitio AS s','s.id','partidas.salida_id')
      ->join('empleados AS e','e.id','s.empleado_solicita_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->join('proyectos AS p2','p2.id','s.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->whereIn('p.nombre',['Proyectos','Servicios'])
      ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','2')
      ->select(
      'partidas.cantidad AS cantidad_salida',
      's.id as ids',
      DB::raw("('Sitio') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      'p2.nombre_corto AS p_salida',
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      's.empleado_solicita_id AS empleado_id',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
      'oc.folio AS oc_folio')
      ->get()->toArray();

      $array_uno = array_merge($st,$ss);

      $sr = Partidas::
      join('salidasresguardo AS s','s.id','partidas.salida_id')
      ->join('empleados AS e','e.id','s.empleado_solicita_id')
      ->join('lote_almacen AS la','la.id','partidas.lote_id')
      ->join('lotes AS l','l.id','la.lote_id')
      ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
      ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
      ->join('articulos AS a','a.id','=','la.articulo_id')
      ->join('stocks AS ss','ss.id','la.stocke_id')
      ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
      ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
      ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
      ->whereIn('p.nombre',['Proyectos','Servicios'])
      ->where('a.nombre','LIKE','%'.$request->articulo.'%')
      ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
      ->where('partidas.tiposalida_id','3')
      ->select(
      's.id as ids',
      DB::raw("('Resguardo') AS tipo"),
      's.folio as folio',
      'p.nombre_corto',
      'a.nombre AS nombre',
      'a.descripcion AS desc',
      'a.unidad as unidad',
      's.empleado_solicita_id AS empleado_id',
      DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
      'oc.folio AS oc_folio')
      ->get()->toArray();

      $array = array_merge($array_uno,$sr);

      return response()->json($array);
    }


    public function salidasProyecto(Request $request)
      {
        // Salidas taller
        $st = Partidas::
        join('salidas AS s','s.id','partidas.salida_id')
        ->join('empleados AS e','e.id','s.empleado_id')
        ->join('lote_almacen AS la','la.id','partidas.lote_id')
        ->join('lotes AS l','l.id','la.lote_id')
        ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
        ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('articulos AS a','a.id','=','la.articulo_id')
        ->join('stocks AS ss','ss.id','la.stocke_id')
        ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
        ->Join('proyectos AS p2','p2.id','=','s.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
        ->where('s.proyecto_id',$request->proyecto)
        // ->whereIn('p.nombre',['Proyectos','Servicios'])
        // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
        // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
        ->where('partidas.tiposalida_id','1')
        ->select(
        'partidas.cantidad AS cantidad_salida',
        's.id as ids',
        DB::raw("('Taller') AS tipo"),
        's.folio as folio',
        'p.nombre_corto',
        "p2.nombre_corto as p_salida",
        'a.nombre AS nombre',
        'a.descripcion AS desc',
        'a.unidad as unidad',
        's.empleado_id',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
        'oc.folio AS oc_folio')
        ->get()->toArray();

        // Salidas sitio
        $ss = Partidas::
        join('salidassitio AS s','s.id','partidas.salida_id')
        ->join('empleados AS e','e.id','s.empleado_solicita_id')
        ->join('lote_almacen AS la','la.id','partidas.lote_id')
        ->join('lotes AS l','l.id','la.lote_id')
        ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
        ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('articulos AS a','a.id','=','la.articulo_id')
        ->join('stocks AS ss','ss.id','la.stocke_id')
        ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
        ->join('proyectos AS p2','p2.id','s.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
        ->where('s.proyecto_id',$request->proyecto)
        // ->whereIn('p.nombre',['Proyectos','Servicios'])
        // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
        // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
        ->where('partidas.tiposalida_id','2')
        ->select(
        'partidas.cantidad AS cantidad_salida',
        's.id as ids',
        DB::raw("('Sitio') AS tipo"),
        's.folio as folio',
        'p.nombre_corto',
        'p2.nombre_corto AS p_salida',
        'a.nombre AS nombre',
        'a.descripcion AS desc',
        'a.unidad as unidad',
        's.empleado_solicita_id AS empleado_id',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
        'oc.folio AS oc_folio')
        ->get()->toArray();

        $array_uno = array_merge($st,$ss);

        $sr = Partidas::
        join('salidasresguardo AS s','s.id','partidas.salida_id')
        ->join('empleados AS e','e.id','s.empleado_solicita_id')
        ->join('lote_almacen AS la','la.id','partidas.lote_id')
        ->join('lotes AS l','l.id','la.lote_id')
        ->join('partidas_entradas AS pe','pe.id','l.entrada_id')
        ->join('requisicion_has_ordencompras AS rhoc','rhoc.id','pe.req_com_id')
        ->join('ordenes_compras AS oc','oc.id','rhoc.orden_compra_id')
        ->join('articulos AS a','a.id','=','la.articulo_id')
        ->join('stocks AS ss','ss.id','la.stocke_id')
        ->Join('proyectos AS p','p.id','=','ss.proyecto_id')
        ->leftJoin('proyecto_subcategorias AS ps','ps.id','=','p.proyecto_subcategorias_id')
        ->leftJoin('proyecto_categorias AS pc','pc.id','=','ps.proyecto_categoria_id')
        ->where('s.proyecto_id',$request->proyecto)
        // ->whereIn('p.nombre',['Proyectos','Servicios'])
        // ->where('a.nombre','LIKE','%'.$request->articulo.'%')
        // ->orWhere('a.descripcion','LIKE','%'.$request->articulo.'%')
        ->where('partidas.tiposalida_id','3')
        ->select(
        's.id as ids',
        DB::raw("('Resguardo') AS tipo"),
        's.folio as folio',
        'p.nombre_corto',
        'a.nombre AS nombre',
        'a.descripcion AS desc',
        'a.unidad as unidad',
        's.empleado_solicita_id AS empleado_id',
        DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_solicita"),
        'oc.folio AS oc_folio')
        ->get()->toArray();

        $array = array_merge($array_uno,$sr);

        return response()->json($array);
      }



  public function estatus($id)
  {
    $rhoc = DB::table('requisicion_has_ordencompras AS rhc')
    ->join('ordenes_compras AS oc','oc.id','=','rhc.orden_compra_id')
    ->select('rhc.*')
    ->where('oc.proyecto_id',$id)
    ->where('oc.condicion','2')
    ->where('rhc.articulo_id','!=',null)
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

      $entradas = DB::table('lotes AS l')
      ->leftJoin('partidas_entradas AS pe','pe.id','l.entrada_id')
      ->where('req_com_id',$value->id)
      ->select(DB::raw("SUM(l.cantidad) as cantidad_entrada"))
      ->first();

      $oc += $value->cantidad;
      $e += (isset($entradas) == true) ? $entradas->cantidad_entrada : 0;
      $s += (isset($salidas) == true) ? $salidas->cantidad_salida : 0;

    }
    $arreglo = [$oc,$e,$s];
    return response()->json($arreglo);
  }

  public function HistoricoExcel($id)
  {
    try {
      $nombre = '';
      if($id == 0){
        $nombre = 'General';
      }else {
        $pro = DB::table('proyectos AS p')->where('p.id',$id)->first();
        $nombre = str_replace(' ','',$pro->nombre_corto);
      }
      return Excel::download(new ComprasHistoricoExport($id), 'HistoricoCompras'.$nombre.'.xlsx' );

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

  public function agregar_proveedor_real()
  {
    try {
      $oc = DB::table('ordenes_compras')->get();

      foreach ($oc as $key => $value) {
        if ($value->proveedore_csct_id == '') {
          $oc_act = \App\Compras::where('id',$value->id)->first();
          $oc_act->proveedore_csct_id = $value->proveedore_id;
          $oc_act->save();
        }
      }
      return response()->json(['status' => true]);
    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }
  }

}
