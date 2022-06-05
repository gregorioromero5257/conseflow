<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Validator;
use App\Compras;
use Illuminate\Validation\Rule;
use Barryvdh\DomPDF\Facade as PDF;
use Rap2hpoutre\FastExcel\FastExcel;
use \App\Http\Helpers\Utilidades;


class EntradaPdfController extends Controller
{

  /**
  * [pdf Genera un archivo PDF con los datos seleccionados]
  * @param  Int $id [ID de la entrada]
  * @return Response [pdf]
  */
  public function pdf($id)
  {
    $ids = Auth::id();
    $entrada = DB::table('entradas')->select('entradas.*',
    DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombreea"),  DB::raw("CONCAT(EU.nombre,' ',EU.ap_paterno,' ',EU.ap_materno) AS nombreeu")  )
    ->leftJoin('tipo_adquisicion AS ta','ta.id','=','entradas.tipo_adq_id')
    ->leftJoin('tipo_entrada AS te','te.id','=','entradas.tipo_entrada_id')
    ->leftJoin('empleados AS EA','EA.id','=','entradas.empleado_almacen_id')
    ->leftJoin('empleados AS EU','EU.id','=','entradas.empleado_usuario_id')
    ->where('entradas.id','=',$id)->get();

    $partida_entrada = DB::table('partidas_entradas')
    //Datos pertenecientes a partidas entrdas
    ->leftJoin('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
    ->leftJoin('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
    ->leftJoin('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
    ->leftJoin('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
    ->leftJoin('proyectos AS PRO', 'PRO.id', '=', 'OC.proyecto_id')
    ->leftJoin('empleados AS EM','EM.id','=','E.empleado_calidad_id')
    ->leftJoin('proveedores AS p','p.id','=','OC.proveedore_id')
    ->select('partidas_entradas.*','A.unidad AS aunidad','A.id AS aid','A.descripcion AS ad','PRO.nombre_corto AS proyecto', 'A.marca AS amarca','OC.id AS idcompra','OC.folio AS foliocompra','RHC.cantidad AS cantidadcompra',
    'E.id AS entradaid','E.fecha AS fechaentrada','p.razon_social AS razon_social',
    DB::raw("CONCAT(EM.nombre,' ',EM.ap_paterno,' ',EM.ap_materno) AS nombree"))
    ->where('partidas_entradas.entrada_id', '=', $id)
    ->where('partidas_entradas.validacion_calidad','!=','1')->get();
    $count = 1;
    $contador = count($partida_entrada);
    $tamanio = 14 -$contador;
    $pdf = PDF::loadView('pdf.entrada', compact('entrada','partida_entrada','ids','contador','count','tamanio'));
    $paper_size = array(0,0,900,660);
    $pdf->setPaper($paper_size);
    return $pdf->stream();
  }

  /**
  ** NUEVO FORMATO DE ENTRADAS PAL-01/F-01
  **  empleado_almacen_id -> EN LA TABLA entradas ES EL EMPLEADO QUE REALIZA LA ENTRADA (USUARIO AUTENTIFICADO)
  **  empleado_usuario_id -> EN LA TABLA entradas ES EL EMPLEADO QUE AUTORIZA JEFE DIRECTO
  **/
  public function pdfnew($id)
  {
    try {
      //OBTENER EL ENCABEZADO DE LA ENTRADA
      $entrada = DB::table('entradas')->
      select('entradas.*',
      DB::raw("CONCAT(EA.nombre,' ',EA.ap_paterno,' ',EA.ap_materno) AS nombreea"),
      DB::raw("CONCAT(EU.nombre,' ',EU.ap_paterno,' ',EU.ap_materno) AS nombreeu"))
      ->leftJoin('empleados AS EA','EA.id','=','entradas.empleado_almacen_id')
      ->leftJoin('empleados AS EU','EU.id','=','entradas.empleado_usuario_id')
      ->where('entradas.id','=',$id)->first();

      $arreglo_partidas = [];

      $partida_entrada = DB::table('partidas_entradas')
      //Datos pertenecientes a partidas entrdas
      ->join('entradas AS E', 'E.id', '=', 'partidas_entradas.entrada_id')
      ->join('articulos AS A', 'A.id', '=', 'partidas_entradas.articulo_id')
      ->leftJoin('grupos AS G','G.id','=','A.grupo_id')
      ->leftJoin('categorias AS CT','CT.id','=','G.categoria_id')
      ->join('requisicion_has_ordencompras AS RHC','RHC.id','=','partidas_entradas.req_com_id')
      ->join('ordenes_compras AS OC', 'OC.id' , '=', 'RHC.orden_compra_id')
      ->join('proyectos AS PRO', 'PRO.id', '=', 'OC.proyecto_id')
      ->join('proveedores AS p','p.id','=','OC.proveedore_id')
      ->select('partidas_entradas.*','A.unidad AS aunidad','A.id AS aid','A.descripcion AS ad',
      'PRO.nombre_corto AS proyecto', 'A.marca AS amarca','OC.id AS idcompra','OC.folio AS foliocompra',
      'RHC.cantidad AS cantidadcompra','E.id AS entradaid','E.fecha AS fechaentrada','p.razon_social AS razon_social',
      'CT.nombre AS categoria'
      )
      ->where('partidas_entradas.entrada_id', $id)
      ->get();

      $arreglo_partidas []= [
        'partidas' => $partida_entrada,
        'tamanio' => count($partida_entrada),
      ];

      $count = 1;
      $contador = count($partida_entrada);
      $tamanio = 14 -$contador;

      $pdf = PDF::loadView('pdf.entradanew', compact('entrada','arreglo_partidas','contador','count','tamanio'));
      $pdf->getDomPDF()->set_option("enable_php", true);//IMPORTANTE PARA LA PAGINACIÓN
      $pdf->setPaper('A4', 'portrait');
      return $pdf->stream();

    } catch (\Throwable $e) {
      Utilidades::errors($e);
      return view('errors.204');
    }
  }


}
