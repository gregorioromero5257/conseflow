<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
Use Validator;
use \App\Http\Helpers\Utilidades;
use Illuminate\Validation\Rule;

class BusquedaExistenciaController extends Controller
{
    //
    public function buscar($id)
    {
      $arreglo=[];
      $valores= explode('&',$id);
      $proyecto_id = $valores[0];
      $ubicacion_id = $valores[1];
      $stand_id = $valores[2];
      $nivel_id = $valores[3];
      $articulo_id = $valores[4];
      $condicion=0;

  if($proyecto_id == 0 && $ubicacion_id == 0 && $stand_id == 0 && $nivel_id == 0){
    $condicion=1;

      $existencias = DB::table('existencias')
      ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
      ->select('articulos.*','proyectos.nombre as prnom')
      ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
      ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
      ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
      ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
      ->distinct()->get();


    $sumaexistencias = DB::table('existencias')
    ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
    ->select(DB::raw('SUM(existencias.cantidad) AS suma'))
    ->where('existencias.articulo_id','=',$id)
    ->first();


    $articulo=DB::table('existencias')
    ->select('existencias.*','lotes.nombre as lote','p.precio_unitario as precios','articulos.descripcion as art_desc','almacenes.ubicacion_id as alm_ub',
    'niveles.nombre as niv_nom','stands.nombre as stand_nomb','tipo_ubicacion.nombre as tu_n','proyectos.nombre as prnom',
    'almacenes.nombre as alm_nom','p.precio_unitario as punit',DB::raw('(existencias.cantidad * p.precio_unitario) AS Precio'))
    ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
    ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
    ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
    ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
    ->leftJoin('stands','stands.id','=','existencias.stand_id')
    ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
    ->leftJoin('precios AS p','p.lote_id','=','la.id')
    ->Join('lotes','lotes.id','=','existencias.id_lote')
    ->Join('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
    ->Join('stocks','stocks.id','=','partidas_entradas.entrada_id')
    ->Join('proyectos','stocks.proyecto_id','=','proyectos.id')
    ->where('articulos.id','=',$articulo_id)
    ->orderBy('id', 'asc')->get();


    $arreglo[]=[
      'condicion' => '1',
      'articulos' => $articulo,
      'suma' => $sumaexistencias,
    ];
  }



  if($proyecto_id != 0 && $ubicacion_id == 0 && $stand_id == 0 && $nivel_id == 0){
    $condicion=2;

          $existencias = DB::table('existencias')
          ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
          ->select('articulos.*','proyectos.nombre as prnom')
          ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
          ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
          ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
          ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
          ->where('proyectos.id','=',$proyecto_id)
          // ->distinct()
          ->get();
          // code...
        $sumaexistencias = DB::table('existencias')
        ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
        ->select(DB::raw('SUM(existencias.cantidad) AS suma'))
        ->where('existencias.articulo_id','=',$id)
        ->first();

      $articulo=DB::table('existencias')
      ->select('existencias.*','p.precio_unitario as punit',DB::raw('(existencias.cantidad * p.precio_unitario) AS Precio'),'lotes.nombre as lote',
      'p.precio_unitario as precios','articulos.descripcion as art_desc','almacenes.ubicacion_id as alm_ub',
      'niveles.nombre as niv_nom','stands.nombre as stand_nomb','tipo_ubicacion.nombre as tu_n','proyectos.nombre as prnom','almacenes.nombre as alm_nom')

      ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
      ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
      ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
      ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
      ->leftJoin('stands','stands.id','=','existencias.stand_id')
      ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
      ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
      ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
      ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
      ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
      ->leftJoin('precios AS p','p.lote_id','=','la.id')
      // ->where('existencias.articulo_id','=',$id)
        ->where('proyectos.id','=',$id)
      ->orderBy('id', 'asc')->get();
        $arreglo[]=[
          'condicion' => '2',
          'articulos' => $articulo,
          'suma' => $sumaexistencias,
        ];

    }

    if($proyecto_id == 0 && $ubicacion_id != 0 && $stand_id == 0 && $nivel_id == 0){
      $condicion=3;

            $existencias = DB::table('existencias')
            ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
            ->select('articulos.*','proyectos.nombre as prnom')
            ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
            ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
            ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
            ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
            ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
            ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
            ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
            ->leftJoin('precios AS p','p.lote_id','=','la.id')
              ->where('almacenes.ubicacion_id','=',$ubicacion_id)
            ->distinct()
            ->get();
            // code...
          $sumaexistencias = DB::table('existencias')
          ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
          ->select(DB::raw('SUM(existencias.cantidad) AS suma'))
          ->where('existencias.articulo_id','=',$id)
          ->first();

        $articulo=DB::table('existencias')
        ->select('existencias.*','p.precio_unitario as punit',DB::raw('(existencias.cantidad * p.precio_unitario) AS Precio'),'lotes.nombre as lote',
        'p.precio_unitario as precios','articulos.descripcion as art_desc','almacenes.ubicacion_id as alm_ub','almacenes.nombre as alm_nom',
        'niveles.nombre as niv_nom','stands.nombre as stand_nomb','tipo_ubicacion.nombre as tu_n' )

        ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
        ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
        ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
        ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
        ->leftJoin('stands','stands.id','=','existencias.stand_id')
        ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
        ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
        ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
        ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
        ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
        ->leftJoin('precios AS p','p.lote_id','=','la.id')
        // ->where('existencias.articulo_id','=',$id)
          ->where('almacenes.ubicacion_id','=',$id)
        ->orderBy('id', 'asc')->get();
          $arreglo[]=[
            'condicion' => '3',
            'articulos' => $articulo,
            'suma' => $sumaexistencias,
          ];

      }

      if($proyecto_id != 0 && $ubicacion_id != 0 && $stand_id != 0 && $nivel_id == 0){
        $condicion=4;

              $existencias = DB::table('existencias')
              ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
              ->select('articulos.*','proyectos.nombre as prnom')
              ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
              ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
              ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
              ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
              ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
              ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
                ->leftJoin('stands','stands.id','=','existencias.stand_id')
              ->where('existencias.stand_id','=',$stand_id)
              // ->distinct()
              ->get();
              // code...
            $sumaexistencias = DB::table('existencias')
            ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
            ->select(DB::raw('SUM(existencias.cantidad) AS suma'))
            ->where('existencias.articulo_id','=',$id)
            ->first();

          $articulo=DB::table('existencias')
          ->select('existencias.*','p.precio_unitario as punit',DB::raw('(existencias.cantidad * p.precio_unitario) AS Precio'),'lotes.nombre as lote',
          'p.precio_unitario as precios','articulos.descripcion as art_desc','almacenes.ubicacion_id as alm_ub','almacenes.nombre as alm_nom',
          'niveles.nombre as niv_nom','stands.nombre as stand_nomb','tipo_ubicacion.nombre as tu_n','proyectos.nombre as prnom')

          ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
          ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
          ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
          ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
          ->leftJoin('stands','stands.id','=','existencias.stand_id')
          ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
          ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
          ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
          ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
          ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
          ->leftJoin('precios AS p','p.lote_id','=','la.id')
          ->where('existencias.stand_id','=',$stand_id)
          ->orderBy('id', 'asc')->get();
            $arreglo[]=[
              'condicion' => '4',
              'articulos' => $articulo,
              'suma' => $sumaexistencias,
            ];
        }


        if($proyecto_id != 0 && $ubicacion_id != 0 && $stand_id != 0 && $nivel_id != 0){
          $condicion=5;

                $existencias = DB::table('existencias')
                ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
                ->select('articulos.*','proyectos.nombre as prnom')
                ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
                ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
                ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
                ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
                ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
                ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
                  ->leftJoin('stands','stands.id','=','existencias.stand_id')
                    ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
                  ->where('existencias.nivel_id','=',$nivel_id)
                // ->distinct()
                ->get();
                // code...
              $sumaexistencias = DB::table('existencias')
              ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
              ->select(DB::raw('SUM(existencias.cantidad) AS suma'))
              ->where('existencias.articulo_id','=',$id)
              ->first();

            $articulo=DB::table('existencias')
            ->select('existencias.*','p.precio_unitario as punit',DB::raw('(existencias.cantidad * p.precio_unitario) AS Precio'),'lotes.nombre as lote',
            'p.precio_unitario as precios','articulos.descripcion as art_desc','almacenes.ubicacion_id as alm_ub','almacenes.nombre as alm_nom',
            'niveles.nombre as niv_nom','stands.nombre as stand_nomb','tipo_ubicacion.nombre as tu_n','proyectos.nombre as prnom')

            ->leftJoin('articulos','articulos.id','=','existencias.articulo_id')
            ->leftJoin('almacenes','almacenes.id','=','existencias.almacene_id')
            ->leftJoin('tipo_ubicacion','tipo_ubicacion.id','=','almacenes.ubicacion_id')
            ->leftJoin('niveles','niveles.id','=','existencias.nivel_id')
            ->leftJoin('stands','stands.id','=','existencias.stand_id')
            ->leftJoin('lotes','lotes.id','=','existencias.id_lote')
            ->leftJoin('partidas_entradas','partidas_entradas.id','=','lotes.entrada_id')
            ->leftJoin('stocks','stocks.id','=','partidas_entradas.entrada_id')
            ->leftJoin('proyectos','stocks.proyecto_id','=','proyectos.id')
            ->leftJoin('lote_almacen as la','la.lote_id','=','existencias.id_lote')
            ->leftJoin('precios AS p','p.lote_id','=','la.id')
          ->where('existencias.nivel_id','=',$nivel_id)
            ->orderBy('id', 'asc')->get();
              $arreglo[]=[
                'condicion' => '5',
                'articulos' => $articulo,
                'suma' => $sumaexistencias,
              ];
          }

        return response()->json($arreglo);


    }
}
