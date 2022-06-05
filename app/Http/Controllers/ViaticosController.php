<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Repositories\Viaticos;
use App\ReporteGastosViaticos;
use App\FacturaDuplicada;
use Illuminate\Support\Facades\Auth;
use \App\Http\Helpers\Utilidades;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ViaticosGastosImport;
use App\ViaticosGastosMenores;
use App\ComprobacionGastosMenores;
use App\Exports\ReporteGralViaticosExport;





class ViaticosController extends Controller
{
  protected $viatico;

  public function __construct(Viaticos $viatico)
  {
    $this->viaticos = $viatico;
  }

  /**
  * Ingresar un nuevo viatico
  *
  * @param Request $request
  * @return \Illuminate\Http\Response
  */
  public function store(Request $request)
  {
    $viatico = \App\Viaticos::where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)->where('pda','=',$request->pda)->first();
    if (isset($viatico) != true) {
      $viaticos = new \App\Viaticos();
      $viaticos->solicitud_viaticos_id = $request->solicitud_viaticos_id;
      $viaticos->gastos_comprobados_deducibles = $request->gastos_comprobados_deducibles;
      $viaticos->gastos_comprobados_no_deducibles = $request->gastos_comprobados_no_deducibles;
      $viaticos->pda = $request->pda;
      $viaticos->save();

      $this->reporteGastos($request->solicitud_viaticos_id, $request->pda);
      return response()->json(array('status' => true));

    }else {
      DB::table('viaticos')->select('viaticos.*')
      ->where('viaticos.solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
      ->where('viaticos.pda','=',$request->pda)
      ->update([
        'gastos_comprobados_deducibles' => $request->gastos_comprobados_deducibles,
        'gastos_comprobados_no_deducibles' => $request->gastos_comprobados_no_deducibles,
      ]);
      $this->reporteGastos($request->solicitud_viaticos_id, $request->pda);
      return response()->json(array('status' => true));


    }

  }

  /**
  * Seleccionar viaticos de un proyecto
  *
  * @param int $id
  * @return \Illuminate\Http\Response
  */
  public function show($id)
  {
    $arreglo = [];
    $viaticos = DB::table('solicitud_viaticos')
    ->select('solicitud_viaticos.*')
    ->where('solicitud_viaticos.proyecto_id','=',$id)
    ->where('solicitud_viaticos.estado','!=','1')
    ->get();
    foreach ($viaticos as $key => $viatico) {

      $beneficiarios = $this->viaticos->BeneficiarioViatico($viatico->id);
      $detalles_viaticos = $this->viaticos->DetalleViatico($viatico->id);

      $partidas_pagos = $this->viaticos->partidasPagos($viatico->id);
      $arreglo [] = [
        'solicitud' => $viatico,
        'beneficiario' => $beneficiarios,
        'detalle' => $detalles_viaticos,
        'pagos' => $partidas_pagos,
      ];
    }

    return response()->json($arreglo);
  }

  /**
  * Seleccionar viaticos de un proyecto
  *
  * @param int $id
  * @return \Illuminate\Http\Response
  */
  public function getSol($id)
  {
    $valores = explode('&',$id);

    if ($valores[1] == 1) {
      $arreglo = [];
      $viaticos = DB::table('solicitud_viaticos')
      ->select('solicitud_viaticos.*')
      ->where('solicitud_viaticos.proyecto_id','=',$valores[0])
      ->where('solicitud_viaticos.estado','!=','1')
      ->get();
      foreach ($viaticos as $key => $viatico) {

        $beneficiarios = $this->viaticos->BeneficiarioViatico($viatico->id);
        $detalles_viaticos = $this->viaticos->DetalleViatico($viatico->id);

        $partidas_pagos = $this->viaticos->partidasPagos($viatico->id);
        $arreglo [] = [
          'solicitud' => $viatico,
          'beneficiario' => $beneficiarios,
          'detalle' => $detalles_viaticos,
          'pagos' => $partidas_pagos,
        ];
      }

      return response()->json($arreglo);
    }elseif($valores[1] == 2){
      $arreglo = [];
      $viaticos = \App\SolicitudViaticosDBP::
      select('solicitud_viaticos.*')
      ->where('solicitud_viaticos.proyecto_id','=',$valores[0])
      ->where('solicitud_viaticos.estado','!=','1')
      ->get();
      foreach ($viaticos as $key => $viatico) {

        $beneficiarios = $this->viaticos->BeneficiarioViaticoCSCT($viatico->id);
        $detalles_viaticos = $this->viaticos->DetalleViaticoCSCT($viatico->id);

        $partidas_pagos = $this->viaticos->partidasPagosCSCT($viatico->id);
        $arreglo [] = [
          'solicitud' => $viatico,
          'beneficiario' => $beneficiarios,
          'detalle' => $detalles_viaticos,
          'pagos' => $partidas_pagos,
        ];
      }

      return response()->json($arreglo);
    }

  }

  /**
  * Busca un encabezado de viatico por id
  * @param int $id
  * @return \Illuminate\Http\Response
  */
  public function verviaticos($id)
  {
    $viaticos = \App\Viaticos::where('id','=',$id)->first();
    return response()->json($viaticos);
  }

  /**
  * Actualizacion de un encabezado de viatico
  * @param Request request
  * @param Int $id
  * @return \Illuminate\Http\Response
  */
  public function update(Request $request, $id)
  {
    $viaticos = \App\Viaticos::where('id','=',$id)->first();
    $viaticos->tipo = $request->tipo;
    $viaticos->proyecto = $request->proyecto;
    $viaticos->beneficiario = $request->beneficiario;
    $viaticos->importe_TE = $request->importe_TE;
    $viaticos->importe_efectivo = $request->importe_efectivo;
    $viaticos->save();

    return response()->json($viaticos);
  }

  public function reporteGastos($id, $pda)
  {
    $reporte = DB::table('reporte_gastos_viaticos')
    ->select('reporte_gastos_viaticos')->where('solicitud_viaticos_id','=',$id)
    ->where('pda','=',$pda)->update([
      'condicion' => 2,
    ]);
    return true;
  }

  public function rechazareporte(Request $request)
  {
    $reporte = DB::table('reporte_gastos_viaticos')
    ->select('reporte_gastos_viaticos')->where('solicitud_viaticos_id','=',$request->solicitud_viaticos_id)
    ->where('pda','=',$request->pda)->update([
      'condicion' => 3,
    ]);
    return response()->json(array('status' => true));
  }

  public function descargarViaticos($id)
  {
    try {



      $valores = explode('&',$id);
      $id = $valores[0];
      $empresa = $valores[1];
      if ($empresa == 1) {

        $beneficiario_viatico = [];
        $solicitud_viaticos = $this->viaticos->solicitud_consulta()
        ->where('solicitud_viaticos.id',$id)
        ->first();

        $detalle_viatico = \App\DetalleViatico::where('solicitud_viaticos_id','=',$id)->get();

        $beneficiario = $this->viaticos->BeneficiarioViatico($id);
        if (count($beneficiario) != 0 ) {
          $beneficiario_viatico = $beneficiario[0];
        }
        $tet = 0;$et = 0 ; $tt = 0;
        foreach ($detalle_viatico as $key => $value) {
          $tet += $value->transferencia_electronica;
          $et += $value->efectivo;
          $tt += $value->total;
        }
        $vehiculoiv = $this->viaticos->VehiculosItinerarioViaticos($id);
        $personalsv = $this->viaticos->PersonalDetalles($id);

        $pdf = \PDF::loadView('pdf.forvia', compact('solicitud_viaticos','beneficiario_viatico','detalle_viatico',
        'tet','et','tt','vehiculoiv','personalsv'));
        $pdf->setPaper("A4", "portrait");
        return $pdf->stream();

      }elseif ($empresa == 2) {

        $beneficiario_viatico = [];
        $solicitud_viaticos = $this->viaticos->solicitud_consulta_csct()
        ->where('solicitud_viaticos.id',$id)
        ->first();

        $detalle_viatico = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$id)->get();

        $beneficiario = $this->viaticos->BeneficiarioViaticoCSCT($id);
        if (count($beneficiario) != 0 ) {
          $beneficiario_viatico = $beneficiario[0];
        }
        $tet = 0;$et = 0 ; $tt = 0;
        foreach ($detalle_viatico as $key => $value) {
          $tet += $value->transferencia_electronica;
          $et += $value->efectivo;
          $tt += $value->total;
        }
        $vehiculoiv = $this->viaticos->VehiculosItinerarioViaticosCSCT($id);
        $personalsv = $this->viaticos->PersonalDetallesCSCT($id);

        $pdf = \PDF::loadView('pdf.forviacsct', compact('solicitud_viaticos','beneficiario_viatico','detalle_viatico',
        'tet','et','tt','vehiculoiv','personalsv'));
        $pdf->setPaper("A4", "portrait");
        return $pdf->stream();
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

  public function descargarnFij($id)
  {
    try {



      $valores = explode('&',$id);
      $id = $valores[0];
      $empresa = $valores[1];
      if ($empresa == 1) {

        $beneficiario_viatico = [];
        $solicitud_viaticos = $this->viaticos->solicitud_consulta()
        ->where('solicitud_viaticos.id',$id)
        ->first();
        $detalle_viatico = \App\DetalleViatico::where('solicitud_viaticos_id','=',$id)->get();

        $beneficiario = $this->viaticos->BeneficiarioViatico($id);
        if (count($beneficiario) != 0 ) {
          $beneficiario_viatico = $beneficiario[0];
        }
        // return $beneficiario_viatico;
        $tet = 0;$et = 0 ; $tt = 0;
        foreach ($detalle_viatico as $key => $value) {
          $tet += $value->transferencia_electronica;
          $et += $value->efectivo;
          $tt += $value->total;
        }
        // $vehiculoiv = $this->viaticos->VehiculosItinerarioViaticos($id);
        // $personalsv = $this->viaticos->PersonalDetalles($id);

        $pdf = \PDF::loadView('pdf.forfij', compact('solicitud_viaticos','beneficiario_viatico','detalle_viatico',
        'tet','et','tt'));
        $pdf->setPaper("letter", "portrait");
        return $pdf->stream();
      }elseif ($empresa == 2) {
        $beneficiario_viatico = [];
        $solicitud_viaticos = $this->viaticos->solicitud_consulta_csct()
        ->where('solicitud_viaticos.id',$id)
        ->first();

        $detalle_viatico = \App\DetalleViaticoCSCT::where('solicitud_viaticos_id','=',$id)->get();

        $beneficiario = $this->viaticos->BeneficiarioViaticoCSCT($id);
        if (count($beneficiario) != 0 ) {
          $beneficiario_viatico = $beneficiario[0];
        }
        $tet = 0;$et = 0 ; $tt = 0;
        foreach ($detalle_viatico as $key => $value) {
          $tet += $value->transferencia_electronica;
          $et += $value->efectivo;
          $tt += $value->total;
        }
        // $vehiculoiv = $this->viaticos->VehiculosItinerarioViaticos($id);
        // $personalsv = $this->viaticos->PersonalDetalles($id);

        $pdf = \PDF::loadView('pdf.forfijcsct', compact('solicitud_viaticos','beneficiario_viatico','detalle_viatico',
        'tet','et','tt'));
        $pdf->setPaper("letter", "portrait");
        return $pdf->stream();
      }
    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

  public function pagosviaticos($id)
  {
    $partidas_pagos = $this->viaticos->partidasPagos($id);
    return response()->json($partidas_pagos);
  }

  public function verfacturasduplicadas($id)
  {
    $valores =  explode('&',$id);
    $reporte_gastos_viaticos = ReporteGastosViaticos::select('reporte_gastos_viaticos.*',
    DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS empleado_nombre"))
    ->join('solicitud_viaticos AS sv','sv.id','=','reporte_gastos_viaticos.solicitud_viaticos_id')
    ->join('empleados AS e','e.id','=','reporte_gastos_viaticos.beneficiario_id')
    ->where('reporte_gastos_viaticos.foliosat','=',$valores[0])
    ->first();


    if (isset($reporte_gastos_viaticos) == true) {
      if ($reporte_gastos_viaticos->beneficiario_id != $valores[2] && $reporte_gastos_viaticos->solicitud_id != $valores[1] ) {
        return response()->json(array('condicion' => 'si','empleado' => $reporte_gastos_viaticos,'foliosat' => $valores[0]));
      }else {
        return response()->json(array('condicion' => 'no'));
      }
    }
    else {
      return response()->json(array('condicion' => 'no'));
    }

  }

  public function facturaduplicadaguardar()
  {
    $valores = explode('&',$id);
    $user = Auth::user();
    $factura_duplicada = new FacturaDuplicada();
    $factura_duplicada->id_emp_usr = $user == null ? $user->id : $user->empleado_id;
    $factura_duplicada->tipo = $user == null ? 0 : 1;
    $factura_duplicada->solicitud_id = $valores[0];
    $factura_duplicada->pda = $valores[1];
    $factura_duplicada->save();
    return response()->json(array('status' => true));
  }

  public function obtenerfacturareporte($id)
  {
    $valores = explode("&",$id);
    $solicitud_id = $valores[1];
    $pda = $valores[2];
    $archivos = DB::table('reporte_gastos_viaticos')
    ->where('reporte_gastos_viaticos.solicitud_viaticos_id', '=', $solicitud_id)
    ->where('reporte_gastos_viaticos.pda', '=', $pda)
    ->first();
    if ($valores[0] == 1) {
      $nombre_archivo = $archivos->adjunto;
      // Se obtiene el archivo del FTP serve
      $archivo = Utilidades::ftpSolucion($nombre_archivo);
      // Se coloca el archivo en una ruta local
      Storage::disk('cotizaciones')->put($nombre_archivo, $archivo);
    }
    if ($valores[0] == 2) {
      $nombre_archivo = $archivos->adjunto;
      //elimina de la ruta local el archivo descargado
      Storage::disk('cotizaciones')->delete($nombre_archivo);
    }

    return response()->json(array(
      'status' => true
    ));
  }

  public function registro(Request $request)
  {
    try {
      if (!$request->ajax()) return redirect('/');
      if($request->hasFile('import_file')){
        ini_set('max_execution_time', 300);
        $collection = Excel::import(new ViaticosGastosImport, request()->file('import_file'));

        return response()->json($collection);

      }
    } catch (\Exception $e) {
      return response()->json($e);
    }

  }

  public function ver()
  {
    $viaticos = ViaticosGastosMenores::leftJoin('proyectos AS p','p.id','=','viaticos_gastos_menores.proyecto_id')
    ->leftJoin('empleados AS e','e.id','=','viaticos_gastos_menores.empleado_id')
    ->leftJoin('catalogo_centro_costos AS ccc','ccc.id','=','centrocostos_id')
    ->leftJoin('catalogo_centro_costos AS ccs','ccs.id','=','ccc.catalogo_centro_costos_id')
    ->leftJoin('catalogo_tipo_viaticos_gastosmenores AS ctvg','ctvg.id','=','tipo')
    ->select('viaticos_gastos_menores.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS nombre_empleado"),'p.nombre_corto',
    DB::raw("CONCAT(ccc.nombre,' ',ccs.nombre) AS nombre_centro"),'ctvg.nombre AS tipo_n')->get();

    return response()->json($viaticos);
  }

  public function guardarComprobacion(Request $request)
  {

    if (!$request->ajax()) return redirect('/');

    /***************************************************************************/
    if ($request->metodo == 'Nuevo') {
      $reporte = new ComprobacionGastosMenores();
      $reporte->viaticos_gastos_menores_id = $request->viaticos_gastos_menores_id;
      $reporte->fecha = $request->fecha;
      $reporte->foliosat = $request->foliosat;
      $reporte->facturainterna = $request->facturainterna;
      $reporte->proveedor_acreedor = $request->proveedor_acreedor;
      $reporte->concepto = $request->concepto;
      $reporte->importediecisies = $request->importediecisies;
      $reporte->importecero = $request->importecero;
      $reporte->iva = $request->iva;
      $reporte->derechos = $request->derechos;
      $reporte->otros_impuestos = $request->otros_impuestos;
      $reporte->no_deducible = $request->no_deducible;
      $reporte->total = $request->total;
      $reporte->catalogo_conceptos_viaticos_id = $request->catalogo_conceptos_viaticos_id;
      $reporte->folio_peaje = $request->folio_peaje;

      if ($request->hasFile('adjunto')) {
        $nombre_archivo = $this->obtenerNombre($request);
        $reporte->adjunto = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo);
      }

      $reporte->save();

      return response()->json(array('status' => true));
    }
    /*****************************************************************/
    if ($request->metodo == 'Actualizar') {
      $nombre_archivo_actualizar = '';

      $reporte = ComprobacionGastosMenores::where('id',$request->viaticos_gastos_menores_id)->first();
      // $reporte->viaticos_gastos_menores_id = $request->viaticos_gastos_menores_id;
      $reporte->fecha = $request->fecha;
      $reporte->foliosat = $request->foliosat;
      $reporte->facturainterna = $request->facturainterna;
      $reporte->proveedor_acreedor = $request->proveedor_acreedor;
      $reporte->concepto = $request->concepto;
      $reporte->importediecisies = $request->importediecisies;
      $reporte->importecero = $request->importecero;
      $reporte->iva = $request->iva;
      $reporte->derechos = $request->derechos;
      $reporte->otros_impuestos = $request->otros_impuestos;
      $reporte->no_deducible = $request->no_deducible;
      $reporte->total = $request->total;
      $reporte->catalogo_conceptos_viaticos_id = $request->catalogo_conceptos_viaticos_id;
      $reporte->folio_peaje = $request->folio_peaje;

      if ($request->hasFile('adjunto')) {
        if ($reporte->adjunto != '') {
          //Elimina el archivo en el servidor si requiere ser actualizado
          Utilidades::ftpSolucionEliminar($reporte->adjunto);
        }
        $nombre_archivo = $this->obtenerNombre($request);
        $reporte->adjunto = $nombre_archivo;
        $this->subirAdjunto($request, $nombre_archivo);
      }

      $reporte->save();

      return response()->json(array('status' => true));

    }

    // return response()->json($request);
  }

  public function obtenerNombre($request)
  {
    //obtiene el nombre del archivo y su extension
    $FacturaNE = $request->file('adjunto')->getClientOriginalName();
    //Obtiene el nombre del archivo
    $FacturaNombre = pathinfo($FacturaNE, PATHINFO_FILENAME);
    //obtiene la extension
    $FacturaExt = $request->file('adjunto')->getClientOriginalExtension();
    //nombre que se guarad en BD
    $FacturaStore = 'ReporteGastoM_'.uniqid().'.'.$FacturaExt;

    return $FacturaStore;
  }


  public function subirAdjunto($request, $nombre_archivo)
  {
    Storage::disk('local')->put('Archivos/'.$nombre_archivo, fopen($request->file('adjunto'), 'r+'));
    return true;
  }

  public function verComprobaciones($id)
  {
    $comprobacion = ComprobacionGastosMenores::where('viaticos_gastos_menores_id',$id)->get();
    return response()->json($comprobacion);
  }

  public function descargarfactura($id)
  {
    // Se obtiene el archivo del FTP serve
    $archivo = Utilidades::ftpSolucion($id);
    // $archivo = Storage::disk('local')->get('ViaticosReporteGastos/'.$id);
    // Se coloca el archivo en una ruta local
    Storage::disk('descarga')->put($id, $archivo);
    //--Devuelve la respuesta y descarga el archivo--//
    return response()->download(storage_path().'/app/descargas/'.$id);
  }

  public function eliminararch($id)
  {
    Storage::disk('descarga')->delete($id);
    Storage::disk('local')->delete($id);

  }

  public function descargarReporteGral($id)
  {
    // code...
    return Excel::download(new ReporteGralViaticosExport($id), 'ReporteGralViaticos.xlsx');

  }

  public function descargarReporteGralPdf($id)
  {
    // code...
    // descargarReporteGral
    $arreglo=[];
    $user = Auth::user();

    if ($id == 1) {////CONSERFLOW
      $controller = new SolicitudViaticosController(new Viaticos([]));
      $solicitudes=$controller->ObtenerViaticosConser($user->empleado_id);

      foreach ($solicitudes as $key => $value) {
        $beneficiario = \App\BeneficiarioViatico::
        join('empleados AS e','e.id','beneficiario_viatico.empleado_beneficiario_id')
        ->select('beneficiario_viatico.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS beneficiario"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $pagos = \App\PartidasViaticosPagos::where('solicitud_viaticos_id',$value->id)->get();
        $pagos_t = \App\PartidasViaticosPagos::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(importe_te) AS total_uno"),DB::raw("SUM(importe_efectivo) AS total_dos"))->first();
        $total_pagos = $pagos_t->total_uno + $pagos_t->total_dos;

        $comprobacion = \App\ReporteGastosViaticos::where('solicitud_viaticos_id',$value->id)->get();
        $comprobacion_t = \App\ReporteGastosViaticos::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(total) AS total"))
        ->first();


        $detalles = \App\DetalleViatico::
        select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $arreglo [] = [
          'solicitud' => $value,
          'detalle' => $detalles,
          'beneficiario' => $beneficiario,
          'pagos' => $pagos,
          'total_pago' => $total_pagos,
          'comprobacion' => $comprobacion,
          'total_comprobacion' => $comprobacion_t->total,
        ];
      }
    }elseif ($id == 2) {////CSCT

      $empleado_cfw = \App\Empleado::where('id',$user->empleado_id)->select('nombre','ap_paterno','ap_materno')->first();
      $empleado_csct = \App\EmpleadoDBP::where('nombre',$empleado_cfw->nombre)->where('ap_paterno',$empleado_cfw->ap_paterno)->where('ap_materno',$empleado_cfw->ap_materno)->first();

      $controller = new SolicitudViaticosController(new Viaticos([]));
      $solicitudes=$controller->ObtenerViaticosCsct($empleado_csct->id);

      foreach ($solicitudes as $key => $value) {
        $beneficiario = \App\BeneficiarioViaticoDBP::
        join('empleados AS e','e.id','beneficiario_viatico.empleado_beneficiario_id')
        ->select('beneficiario_viatico.*',DB::raw("CONCAT(e.nombre,' ',e.ap_paterno,' ',e.ap_materno) AS beneficiario"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $pagos = \App\PartidasViaticosPagosDBP::where('solicitud_viaticos_id',$value->id)->get();
        $pagos_t = \App\PartidasViaticosPagosDBP::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(importe_te) AS total_uno"),DB::raw("SUM(importe_efectivo) AS total_dos"))->first();
        $total_pagos = $pagos_t->total_uno + $pagos_t->total_dos;

        $comprobacion = \App\ReporteGastosViaticosDBP::where('solicitud_viaticos_id',$value->id)->get();
        $comprobacion_t = \App\ReporteGastosViaticosDBP::where('solicitud_viaticos_id',$value->id)
        ->select(DB::raw("SUM(total) AS total"))
        ->first();
        $detalles = \App\DetalleViaticoCSCT::
        select(DB::raw("SUM(transferencia_electronica) AS transferencia"),
        DB::raw("SUM(efectivo) AS efectivo"),
        DB::raw("SUM(total) AS total"))
        ->where('solicitud_viaticos_id',$value->id)->first();

        $arreglo [] = [
          'solicitud' => $value,
          'detalle' => $detalles,
          'beneficiario' => $beneficiario,
          'pagos' => $pagos,
          'total_pago' => $total_pagos,
          'comprobacion' => $comprobacion,
          'total_comprobacion' => $comprobacion_t->total,
        ];
      }
    }


    $empresa = '';
    if ($id == 1) {
      $empresa = 'CONSERFLOW S.A. DE C.V.';
    }elseif ($id == 2) {
      $empresa = 'CSCT';
    }
    // $empresa = $id == 1 ? 'CONSERFLOW S.A. DE C.V.' : 'CSCT' ;


    $pdf = \PDF::loadView('pdf.reporteviaticos', compact('arreglo','empresa'));
    $pdf->setPaper("letter", "portrait");
    return $pdf->stream();
    // return Excel::download(new ReporteGralViaticosExport($id), 'ReporteGralViaticos.xlsx');

  }


}
