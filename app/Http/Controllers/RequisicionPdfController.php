<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Validator;
use App\Requisicion;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use \App\Http\Helpers\Utilidades;



class RequisicionPdfController extends Controller
{

  public function pdf($id)
  {
    $partidas = [];
    $requisicion = \App\Requisicion::where('requisiciones.id', '=', $id)
    ->leftJoin('departamentos', 'departamentos.id', '=', 'requisiciones.area_solicita_id')
    ->leftJoin('proyectos', 'proyectos.id', '=', 'requisiciones.proyecto_id')
    ->leftJoin('estado_requisiciones', 'estado_requisiciones.id', '=', 'requisiciones.estado_id')
    ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
    ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
    ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
    ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
    ->leftJoin('empleados AS eal', 'eal.id', '=', 'requisiciones.almacen_empleado_id')
    ->select('requisiciones.*',
    'departamentos.nombre AS areasolicitante',
    'proyectos.nombre AS proyecto',
    'estado_requisiciones.nombre AS estadorequisicion',
    DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombres"),
    DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombrea"),
    DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombrev"),
    DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombrer"),
    DB::raw("CONCAT(eal.nombre,' ',eal.ap_paterno,' ',eal.ap_materno) AS nombreal")
    )
    ->get()->first();


    $partidasRE = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)
    ->leftJoin('articulos','articulos.id', '=', 'partidas_requisiciones.articulo_id')
    ->leftJoin('servicios','servicios.id', '=', 'partidas_requisiciones.servicio_id')
    ->leftJoin('requisiciones', 'requisiciones.id', '=', 'partidas_requisiciones.requisicione_id')
    ->select('partidas_requisiciones.*',
    'articulos.id AS idarticulo',
    'articulos.nombre AS nombrearticulo',
    'articulos.codigo AS codigoarticulo',
    'articulos.descripcion AS descripcionarticulo',
    'articulos.marca AS marcaarticulo',
    'articulos.unidad AS unidadarticulo',
    'servicios.id AS idservicio',
    'servicios.nombre_servicio AS descripcionservicio',
    'servicios.proveedor_marca AS marcaservicio',
    'servicios.unidad_medida AS unidadservicio')
    ->get();
    foreach ($partidasRE as $key => $value) {
      $articulo_id =$value->idarticulo;
      $servicio_id = $value->idservicio;
      $doc = [];
      if (!is_null($articulo_id)) {
        $doc = DB::table('partidarequisicion_documentos')
        ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
        ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
        ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
        ->where('partidarequisicion_documentos.partidarequisicione_art','=',$articulo_id)
        // ->where('partidarequisicion_documentos.partidarequisicione_serv','=',$value->idservicio)
        ->get();
      }
      if (!is_null($servicio_id)) {
        $doc = DB::table('partidarequisicion_documentos')
        ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
        ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
        ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
        ->where('partidarequisicion_documentos.partidarequisicione_serv','=',$servicio_id)
        ->get();
      }
      $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id',$id)
      // ->where('pda',$value->pda)
      ->where('articulo_servicio','1')->where('articulo_servicio_id',$articulo_id)->first();

      $partidas [] =[
        'par' => $value,
        'doc' => $doc,
        'com' => $comentarios,
      ];
    }

    // $doc PartidaRequisicionDoc
    $count =1;
    $partidascount = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)->count();
    $anio = substr($requisicion->fecha_solicitud, 2 ,2);
    $mes = substr($requisicion->fecha_solicitud, 5 , -3);
    $dia = substr($requisicion->fecha_solicitud, 8);

    $mesnombre = $this->getMes($mes);
    $fechafinal = $dia.'-'.$mesnombre.'-'.$anio;

    $fechaSolicitud = substr($requisicion->fecha_solicitud, 8).'-'.$this->getMes(substr($requisicion->fecha_solicitud, 5 , -3)).'-'.substr($requisicion->fecha_solicitud, 2 ,2);
    $fechaAutorizada = substr($requisicion->fecha_autorizada, 8).'-'.$this->getMes(substr($requisicion->fecha_autorizada, 5 , -3)).'-'.substr($requisicion->fecha_autorizada, 2 ,2);
    $fechaCompletada = substr($requisicion->fecha_completada, 8).'-'.$this->getMes(substr($requisicion->fecha_completada, 5 , -3)).'-'.substr($requisicion->fecha_completada, 2 ,2);
    $fechaRecibida = substr($requisicion->fecha_recibida, 8).'-'.$this->getMes(substr($requisicion->fecha_recibida, 5 , -3)).'-'.substr($requisicion->fecha_recibida, 2 ,2);
    $valor = 23;
    $valora = $valor - $partidascount;

    $pdf = PDF::loadView('pdf.requisicion', compact('requisicion', 'partidas','fechafinal', 'fechaSolicitud','fechaAutorizada','fechaCompletada','fechaRecibida','partidascount','count','valora'));
    $pdf->setPaper('A4', 'landscape');
    // return $pdf->download('cv-interno.pdf');
    return $pdf->stream();
  }

  private function getMes($numMes)
  {
    $mesnombre ='';
    switch ($numMes) {
      case '01': $mesnombre = 'ene'; break;
      case '02': $mesnombre = 'feb'; break;
      case '03': $mesnombre = 'mar'; break;
      case '04': $mesnombre = 'abr'; break;
      case '05': $mesnombre = 'may'; break;
      case '06': $mesnombre = 'jun'; break;
      case '07': $mesnombre = 'jul'; break;
      case '08': $mesnombre = 'ago'; break;
      case '09': $mesnombre = 'sep'; break;
      case '10': $mesnombre = 'oct'; break;
      case '11': $mesnombre = 'nov'; break;
      case '12': $mesnombre = 'dic'; break;
    }
    return $mesnombre;
  }


  public function pdfCorreo($id)
  {
    try {
      $partidas = [];
      $proyecto_ = Requisicion::
      where('requisiciones.id','=',$id)->first();
      $requisicion = \App\Requisicion::where('requisiciones.id', '=', $id)
      ->leftJoin('departamentos', 'departamentos.id', '=', 'requisiciones.area_solicita_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'requisiciones.proyecto_id')
      ->leftJoin('estado_requisiciones', 'estado_requisiciones.id', '=', 'requisiciones.estado_id')
      ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
      ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
      ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
      ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
      ->leftJoin('empleados AS eal', 'eal.id', '=', 'requisiciones.almacen_empleado_id')
      ->select('requisiciones.*',
      'departamentos.nombre AS areasolicitante',
      'proyectos.nombre AS proyecto',
      'estado_requisiciones.nombre AS estadorequisicion',
      DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombres"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombrea"),
      DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombrev"),
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombrer"),
      DB::raw("CONCAT(eal.nombre,' ',eal.ap_paterno,' ',eal.ap_materno) AS nombreal")
      )
      ->get()->first();


      $partidasRE = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)
      ->leftJoin('articulos','articulos.id', '=', 'partidas_requisiciones.articulo_id')
      ->leftJoin('servicios','servicios.id', '=', 'partidas_requisiciones.servicio_id')
      ->leftJoin('requisiciones', 'requisiciones.id', '=', 'partidas_requisiciones.requisicione_id')
      ->select('partidas_requisiciones.*',
      'articulos.id AS idarticulo',
      'articulos.nombre AS nombrearticulo',
      'articulos.codigo AS codigoarticulo',
      'articulos.descripcion AS descripcionarticulo',
      'articulos.marca AS marcaarticulo',
      'articulos.unidad AS unidadarticulo',
      'servicios.id AS idservicio',
      'servicios.nombre_servicio AS descripcionservicio',
      'servicios.proveedor_marca AS marcaservicio',
      'servicios.unidad_medida AS unidadservicio')
      ->get();
      foreach ($partidasRE as $key => $value) {
        $articulo_id =$value->idarticulo;
        $servicio_id = $value->idservicio;
        $doc = [];
        if (!is_null($articulo_id)) {
          $doc = DB::table('partidarequisicion_documentos')
          ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
          ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
          ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
          ->where('partidarequisicion_documentos.partidarequisicione_art','=',$articulo_id)
          ->get();
        }
        if (!is_null($servicio_id)) {
          $doc = DB::table('partidarequisicion_documentos')
          ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
          ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
          ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
          ->where('partidarequisicion_documentos.partidarequisicione_serv','=',$servicio_id)
          ->get();
        }

        $partidas [] =[
          'par' => $value,
          'doc' => $doc,
        ];
      }

      // $doc PartidaRequisicionDoc
      $count =1;
      $partidascount = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)->count();
      $anio = substr($requisicion->fecha_solicitud, 2 ,2);
      $mes = substr($requisicion->fecha_solicitud, 5 , -3);
      $dia = substr($requisicion->fecha_solicitud, 8);

      $mesnombre = $this->getMes($mes);
      $fechafinal = $dia.'-'.$mesnombre.'-'.$anio;

      $numero_proyecto = 0;
      if ($proyecto_->proyecto_id > 100) {
        $numero_proyecto = $proyecto_->proyecto_id - 100;
      }else {
        $numero_proyecto = $proyecto_->proyecto_id;
      }

      $folio_com = explode('-',$requisicion->folio);
      $nombre_corto_proyecto = $folio_com[1];
      $numero_pedido = '100'.str_pad($numero_proyecto, 3, "0", STR_PAD_LEFT).str_pad($folio_com[2], 4, "0", STR_PAD_LEFT);

      $fechaSolicitud = substr($requisicion->fecha_solicitud, 8).'-'.$this->getMes(substr($requisicion->fecha_solicitud, 5 , -3)).'-'.substr($requisicion->fecha_solicitud, 2 ,2);
      $fechaAutorizada = substr($requisicion->fecha_autorizada, 8).'-'.$this->getMes(substr($requisicion->fecha_autorizada, 5 , -3)).'-'.substr($requisicion->fecha_autorizada, 2 ,2);
      $fechaCompletada = substr($requisicion->fecha_completada, 8).'-'.$this->getMes(substr($requisicion->fecha_completada, 5 , -3)).'-'.substr($requisicion->fecha_completada, 2 ,2);
      $fechaRecibida = substr($requisicion->fecha_recibida, 8).'-'.$this->getMes(substr($requisicion->fecha_recibida, 5 , -3)).'-'.substr($requisicion->fecha_recibida, 2 ,2);
      $valor = 32;
      $valora = $valor - $partidascount;

      return [
        'requisicion' => $requisicion,
        'partidas' => $partidas,
        'fechafinal' => $fechafinal,
        'fechaSolicitud' => $fechaSolicitud,
        'fechaAutorizada' => $fechaAutorizada,
        'fechaCompletada' => $fechaCompletada,
        'fechaRecibida' => $fechaRecibida,
        'partidascount' => $partidascount,
        'count' => $count,
        'valora' => $valora,
        'nombre_corto_proyecto' => $nombre_corto_proyecto,
        'numero_pedido' => $numero_pedido,
      ];

    } catch (\Throwable $e) {
      Utilidades::errors($e);
    }


  }


  public function pdfnew($id)
  {
    try {

      $partidas = [];
      $proyecto_ = Requisicion::where('requisiciones.id','=',$id)->first();

      $requisicion = \App\Requisicion::where('requisiciones.id', '=', $id)
      ->leftJoin('departamentos', 'departamentos.id', '=', 'requisiciones.area_solicita_id')
      ->leftJoin('proyectos', 'proyectos.id', '=', 'requisiciones.proyecto_id')
      ->leftJoin('estado_requisiciones', 'estado_requisiciones.id', '=', 'requisiciones.estado_id')
      ->leftJoin('empleados AS es', 'es.id', '=', 'requisiciones.solicita_empleado_id')
      ->leftJoin('empleados AS ea', 'ea.id', '=', 'requisiciones.autoriza_empleado_id')
      ->leftJoin('empleados AS ev', 'ev.id', '=', 'requisiciones.valida_empleado_id')
      ->leftJoin('empleados AS er', 'er.id', '=', 'requisiciones.recibe_empleado_id')
      ->leftJoin('empleados AS eal', 'eal.id', '=', 'requisiciones.almacen_empleado_id')
      ->select('requisiciones.*',
      'departamentos.nombre AS areasolicitante',
      'proyectos.nombre AS proyecto',
      'estado_requisiciones.nombre AS estadorequisicion',
      DB::raw("CONCAT(es.nombre,' ',es.ap_paterno,' ',es.ap_materno) AS nombres"),
      DB::raw("CONCAT(ea.nombre,' ',ea.ap_paterno,' ',ea.ap_materno) AS nombrea"),
      DB::raw("CONCAT(ev.nombre,' ',ev.ap_paterno,' ',ev.ap_materno) AS nombrev"),
      DB::raw("CONCAT(er.nombre,' ',er.ap_paterno,' ',er.ap_materno) AS nombrer"),
      DB::raw("CONCAT(eal.nombre,' ',eal.ap_paterno,' ',eal.ap_materno) AS nombreal")
      )->first();


      $partidasRE = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)
      ->leftJoin('articulos','articulos.id', '=', 'partidas_requisiciones.articulo_id')
      ->leftJoin('servicios','servicios.id', '=', 'partidas_requisiciones.servicio_id')
      ->leftJoin('requisiciones', 'requisiciones.id', '=', 'partidas_requisiciones.requisicione_id')
      ->select('partidas_requisiciones.*',
      'articulos.id AS idarticulo',
      'articulos.nombre AS nombrearticulo',
      'articulos.codigo AS codigoarticulo',
      'articulos.descripcion AS descripcionarticulo',
      'articulos.marca AS marcaarticulo',
      'articulos.unidad AS unidadarticulo',
      'servicios.id AS idservicio',
      'servicios.nombre_servicio AS descripcionservicio',
      'servicios.proveedor_marca AS marcaservicio',
      'servicios.unidad_medida AS unidadservicio')
      ->get();

      foreach ($partidasRE as $key => $value) {

        $articulo_id =$value->idarticulo;
        $servicio_id = $value->idservicio;
        $doc = [];

        if (!is_null($articulo_id)) {
          $doc = DB::table('partidarequisicion_documentos')
          ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
          ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
          ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
          ->where('partidarequisicion_documentos.partidarequisicione_art','=',$articulo_id)
          ->get();
        }
        if (!is_null($servicio_id)) {
          $doc = DB::table('partidarequisicion_documentos')
          ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
          ->select('partidarequisicion_documentos.documento_id','DP.nombre_corto')
          ->where('partidarequisicion_documentos.partidarequisicione_req','=',$id)
          ->where('partidarequisicion_documentos.partidarequisicione_serv','=',$servicio_id)
          ->get();
        }

        $partidas [] =[
          'par' => $value,
          'doc' => $doc,
        ];
      }

      $count =1;
      $partidascount = \App\PartidaRe::where('partidas_requisiciones.requisicione_id', '=', $id)->count();
      $anio = substr($requisicion->fecha_solicitud, 2 ,2);
      $mes = substr($requisicion->fecha_solicitud, 5 , -3);
      $dia = substr($requisicion->fecha_solicitud, 8);

      $mesnombre = $this->getMes($mes);
      $fechafinal = $dia.'.'.$mes.'.'.$anio;

      $numero_proyecto = 0;
      if ($proyecto_->proyecto_id > 100) {
        $numero_proyecto = $proyecto_->proyecto_id - 100;
      }else {
        $numero_proyecto = $proyecto_->proyecto_id;
      }
      $folio_com = explode('-',$requisicion->folio);
      $nombre_corto_proyecto = $folio_com[1];
      $numero_pedido = '100'.str_pad($numero_proyecto, 3, "0", STR_PAD_LEFT).str_pad($folio_com[2], 4, "0", STR_PAD_LEFT);


      $fechaSolicitud = substr($requisicion->fecha_solicitud, 8).'-'.$this->getMes(substr($requisicion->fecha_solicitud, 5 , -3)).'-'.substr($requisicion->fecha_solicitud, 2 ,2);
      $fechaAutorizada = substr($requisicion->fecha_autorizada, 8).'-'.$this->getMes(substr($requisicion->fecha_autorizada, 5 , -3)).'-'.substr($requisicion->fecha_autorizada, 2 ,2);
      $fechaCompletada = substr($requisicion->fecha_completada, 8).'-'.$this->getMes(substr($requisicion->fecha_completada, 5 , -3)).'-'.substr($requisicion->fecha_completada, 2 ,2);
      $fechaRecibida = substr($requisicion->fecha_recibida, 8).'-'.$this->getMes(substr($requisicion->fecha_recibida, 5 , -3)).'-'.substr($requisicion->fecha_recibida, 2 ,2);
      $valor = 23;
      $valora = $valor - $partidascount;

      $pdf = PDF::loadView('pdf.requisicionew', compact('nombre_corto_proyecto',
      'requisicion','numero_pedido', 'partidas','fechafinal',
      'fechaSolicitud','fechaAutorizada','fechaCompletada','fechaRecibida','partidascount','count','valora'));
      $pdf->getDomPDF()->set_option("enable_php", true);
      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }

}
