<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;
use \App\Http\Helpers\Utilidades;
use App\IncidenciasRequisiciones;

class ConsultaDashProyectosController extends Controller
{

  public function show($id)
  {

    $arreglo_art =[];
    $arreglo_serv =[];
      $requisiciones_art = DB::table('requisiciones')
          ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
          ->leftJoin('articulos as a', 'a.id', '=', 'pr.articulo_id')
          ->select('requisiciones.*','a.nombre','a.codigo','a.descripcion','a.marca','pr.peso AS peso',
            'pr.cantidad AS cantidades','pr.equivalente','pr.fecha_requerido AS frequerido',
            'pr.condicion AS condicion','pr.requisicione_id','pr.cantidad_compra','pr.cantidad_almacen',
            'pr.articulo_id','pr.servicio_id','pr.comentario AS comentario_partida','a.nombre AS narticulo',
            'a.codigo AS carticulo','a.descripcion AS darticulo','a.unidad AS unidad_articulo','pr.pda')
          ->where('pr.requisicione_id', '=', $id)
          // ->where('pr.produccion','=','1')
          ->where('pr.articulo_id', '!=', null)
          ->orderBy('requisiciones.id', 'ASC')
          ->get();

          foreach ($requisiciones_art as $key => $value_art) {

            $documentos_art = DB::table('partidarequisicion_documentos')
            ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
            ->select('partidarequisicion_documentos.*','DP.nombre','DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_art','=',$value_art->articulo_id)
            ->where('partidarequisicion_documentos.partidarequisicione_req','=',$value_art->requisicione_id)->get();

            $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id',$value_art->requisicione_id)
            ->where('pda',$value_art->pda)->where('articulo_servicio','1')
            ->where('articulo_servicio_id',$value_art->articulo_id)
            ->where('activo','1')
            ->first();

            $regresados = DB::table('incidencias_requisiciones')->where('requisicion_id',$value_art->requisicione_id)
            ->where('pda',$value_art->pda)->where('articulo_servicio','1')
            ->where('articulo_servicio_id',$value_art->articulo_id)
            ->where('activo','0')
            ->first();


            $arreglo_art [] = [
              'req' => $value_art,
              'doc' => $documentos_art,
              'correccion' => $comentarios,
              'regresados' => $regresados,
            ];
          }

            $requisiciones_serv = DB::table('requisiciones')
                ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
                ->leftJoin('servicios', 'servicios.id', 'pr.servicio_id')
                ->select('requisiciones.*','pr.peso AS peso','pr.cantidad AS cantidades','pr.equivalente',
                  'pr.fecha_requerido AS frequerido','pr.condicion AS condicion','pr.requisicione_id',
                  'pr.cantidad_compra','pr.cantidad_almacen','pr.articulo_id','pr.comentario AS comentario_partida','pr.servicio_id',
                  'servicios.nombre_servicio as descripcion','servicios.proveedor_marca as nproveedor','pr.pda',
                  'servicios.unidad_medida as unidad_articulo'
                  )
                ->where('pr.requisicione_id', '=', $id)
                ->where('pr.servicio_id', '!=', null)
                // ->where('pr.produccion','=','1')
                ->orderBy('requisiciones.id', 'ASC')
                ->get();

                foreach ($requisiciones_serv as $key => $value_serv) {

                  $documentos_serv = DB::table('partidarequisicion_documentos')
                  ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
                  ->select('partidarequisicion_documentos.*','DP.nombre','DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_serv','=',$value_serv->servicio_id)
                  ->where('partidarequisicion_documentos.partidarequisicione_req','=',$value_serv->requisicione_id)->get();

                  $comentarios = DB::table('incidencias_requisiciones')->where('requisicion_id',$value_serv->requisicione_id)
                  ->where('pda',$value_serv->pda)->where('articulo_servicio','0')
                  ->where('articulo_servicio_id',$value_serv->servicio_id)
                  ->where('activo','1')
                  ->first();

                  $regresados = DB::table('incidencias_requisiciones')->where('requisicion_id',$value_serv->requisicione_id)
                  ->where('pda',$value_serv->pda)->where('articulo_servicio','0')
                  ->where('articulo_servicio_id',$value_serv->servicio_id)
                  ->where('activo','0')
                  ->first();


                  $arreglo_serv [] = [
                    'req' => $value_serv,
                    'doc' => $documentos_serv,
                    'correccion' => $comentarios,
                    'regresados' => $regresados,
                  ];
                }

                $results = array_merge($arreglo_art, $arreglo_serv);
                return response()->json($results);
  }

  public function documentosdashproyectos($id)
  {
    $arreglo_art =[];
    $arreglo_serv =[];
      $requisiciones_art = DB::table('requisiciones')
          ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
          ->leftJoin('articulos as a', 'a.id', '=', 'pr.articulo_id')
          ->select('requisiciones.*','a.nombre','a.codigo','a.descripcion','a.marca','pr.peso AS peso',
            'pr.cantidad AS cantidades','pr.equivalente','pr.fecha_requerido AS frequerido',
            'pr.condicion AS condicion','pr.requisicione_id','pr.cantidad_compra','pr.cantidad_almacen',
            'pr.articulo_id','pr.servicio_id','pr.comentario AS comentario_partida','a.nombre AS narticulo',
            'a.codigo AS carticulo','a.descripcion AS darticulo','a.unidad AS unidad_articulo')
          ->where('pr.requisicione_id', '=', $id)
          ->where('pr.articulo_id', '!=', null)
          ->orderBy('requisiciones.id', 'ASC')
          ->get();

          foreach ($requisiciones_art as $key => $value_art) {

            $documentos_art = DB::table('partidarequisicion_documentos')
            ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
            ->select('partidarequisicion_documentos.*','DP.nombre','DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_art','=',$value_art->articulo_id)
            ->where('partidarequisicion_documentos.partidarequisicione_req','=',$value_art->requisicione_id)
            ->where('partidarequisicion_documentos.condicion','=','0')->get();

            $arreglo_art [] = [
              'req' => $value_art,
              'doc' => $documentos_art,
            ];
          }

            $requisiciones_serv = DB::table('requisiciones')
                ->leftJoin('partidas_requisiciones AS pr', 'pr.requisicione_id', '=', 'requisiciones.id')
                ->leftJoin('servicios', 'servicios.id', 'pr.servicio_id')
                ->select('requisiciones.*','pr.peso AS peso','pr.cantidad AS cantidades','pr.equivalente',
                  'pr.fecha_requerido AS frequerido','pr.condicion AS condicion','pr.requisicione_id',
                  'pr.cantidad_compra','pr.cantidad_almacen','pr.articulo_id','pr.comentario AS comentario_partida','pr.servicio_id',
                  'servicios.nombre_servicio as descripcion','servicios.proveedor_marca as nproveedor',
                  'servicios.unidad_medida as unidad_articulo'
                  )
                ->where('pr.requisicione_id', '=', $id)
                ->where('pr.servicio_id', '!=', null)
                ->orderBy('requisiciones.id', 'ASC')
                ->get();

                foreach ($requisiciones_serv as $key => $value_serv) {

                  $documentos_serv = DB::table('partidarequisicion_documentos')
                  ->leftJoin('documentos_proveedores AS DP','DP.id','=','partidarequisicion_documentos.documento_id')
                  ->select('partidarequisicion_documentos.*','DP.nombre','DP.nombre_corto')->where('partidarequisicion_documentos.partidarequisicione_serv','=',$value_serv->servicio_id)
                  ->where('partidarequisicion_documentos.partidarequisicione_req','=',$value_serv->requisicione_id)
                  ->where('partidarequisicion_documentos.condicion','=','0')->get();

                  $arreglo_serv [] = [
                    'req' => $value_serv,
                    'doc' => $documentos_serv,
                  ];
                }

                $results = array_merge($arreglo_art, $arreglo_serv);
                return response()->json($results);
  }

  public function comentarioPartidas(Request $request)
  {

    $ri = IncidenciasRequisiciones::where('requisicion_id',$request->requisicion_id)
    ->where('pda',$request->pda)
    ->where('articulo_servicio',$request->articulo_servicio)
    ->where('articulo_servicio_id',$request->articulo_servicio_id)
    ->where('activo','1')->first();

    if(isset($ri) == false){
      $ri_n = new IncidenciasRequisiciones();
      $ri_n->fill($request->all());
      $ri_n->save();
    }else {
      $ri->comentario = $request->comentario;
      $ri->save();
    }
    return response()->json(array('status' => true));
  }

}
